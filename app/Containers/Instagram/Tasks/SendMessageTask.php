<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use InstagramAPI\Response;
use InstagramAPI\Constants;
use InstagramAPI\Request\Metadata\Internal as InternalMetadata;
use InstagramAPI\Exception\InstagramException;
use InstagramAPI\Exception\ThrottledException;
use InstagramAPI\Exception\UploadFailedException;
use InstagramAPI\Signatures;
use InstagramAPI\Utils;
use Illuminate\Support\Facades\Input;
use App\Containers\Instagram\Tasks\UpdateStatusTask;
use App\Containers\Instagram\Tasks\StoreResponseCommentTask;

class SendMessageTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($ig)
    {
		//dd('sexi');
        if(Input::get('fromcomment')){
            $recipients = array(
                'users' => array(Input::get('user_id'))
            );
            $response = SendMessageTask::sendText($recipients,Input::get('message'),$ig);
            $comment = (new UpdateStatusTask())->run();
            (new StoreResponseCommentTask())->run($comment,$response->payload->thread_id);
        }else{
            $recipients = array(
                'thread' => Input::get('thread')
            );
            $response = SendMessageTask::sendText($recipients,Input::get('message'),$ig);
        }
        $message = (new UpdateStatusTask())->run();
        return json_encode($response);
    }
    public function sendText(array $recipients,$text,$ig,$options = false)
    {
        if (!strlen($text)) {
            throw new \InvalidArgumentException('Text can not be empty.');
        }
        if(!$options){
            $options = [];
        }
        $urls = Utils::extractURLs($text);
        if (count($urls)) {
            /** @var Response\DirectSendItemResponse $result */
            $result = $this->_sendDirectItem('links', $recipients, array_merge($options, [
                'link_urls' => json_encode(array_map(function (array $url) {
                    return $url['fullUrl'];
                }, $urls)),
                'link_text' => $text,
            ]), $ig);
        } else {
            /** @var Response\DirectSendItemResponse $result */
            $result = $this->_sendDirectItem('message', $recipients, array_merge($options, [
                'text' => $text,
            ]), $ig);
        }
        return $result;
    }

    protected function _sendDirectItem(
        $type,
        array $recipients,
        array $options = [],
        $ig)
    {
        // Most requests are unsigned, but some use signing by overriding this.
        $signedPost = false;
        // Handle the request...
        switch ($type) {
            case 'media_share':
                $request = $ig->request('direct_v2/threads/broadcast/media_share/');
                // Check and set media_id.
                if (!isset($options['media_id'])) {
                    throw new \InvalidArgumentException('No media_id provided.');
                }
                $request->addPost('media_id', $options['media_id']);
                // Set text if provided.
                if (isset($options['text']) && strlen($options['text'])) {
                    $request->addPost('text', $options['text']);
                }
                // Check and set media_type.
                if (isset($options['media_type']) && $options['media_type'] === 'video') {
                    $request->addParam('media_type', 'video');
                } else {
                    $request->addParam('media_type', 'photo');
                }
                break;
            case 'message':
                $request = $ig->request('direct_v2/threads/broadcast/text/');
                // Check and set text.
                if (!isset($options['text'])) {
                    throw new \InvalidArgumentException('No text message provided.');
                }
                $request->addPost('text', $options['text']);
                break;
            case 'like':
                $request = $ig->request('direct_v2/threads/broadcast/like/');
                break;
            case 'hashtag':
                $request = $ig->request('direct_v2/threads/broadcast/hashtag/');
                // Check and set hashtag.
                if (!isset($options['hashtag'])) {
                    throw new \InvalidArgumentException('No hashtag provided.');
                }
                $request->addPost('hashtag', $options['hashtag']);
                // Set text if provided.
                if (isset($options['text']) && strlen($options['text'])) {
                    $request->addPost('text', $options['text']);
                }
                break;
            case 'location':
                $request = $ig->request('direct_v2/threads/broadcast/location/');
                // Check and set venue_id.
                if (!isset($options['venue_id'])) {
                    throw new \InvalidArgumentException('No venue_id provided.');
                }
                $request->addPost('venue_id', $options['venue_id']);
                // Set text if provided.
                if (isset($options['text']) && strlen($options['text'])) {
                    $request->addPost('text', $options['text']);
                }
                break;
            case 'profile':
                $request = $ig->request('direct_v2/threads/broadcast/profile/');
                // Check and set profile_user_id.
                if (!isset($options['profile_user_id'])) {
                    throw new \InvalidArgumentException('No profile_user_id provided.');
                }
                $request->addPost('profile_user_id', $options['profile_user_id']);
                // Set text if provided.
                if (isset($options['text']) && strlen($options['text'])) {
                    $request->addPost('text', $options['text']);
                }
                break;
            case 'photo':
                $request = $ig->request('direct_v2/threads/broadcast/upload_photo/');
                // Check and set filepath.
                if (!isset($options['filepath'])) {
                    throw new \InvalidArgumentException('No filepath provided.');
                }
                $request->addFile('photo', $options['filepath'], 'direct_temp_photo_'.Utils::generateUploadId().'.jpg');
                break;
            case 'video':
                $request = $ig->request('direct_v2/threads/broadcast/configure_video/');
                // Check and set upload_id.
                if (!isset($options['upload_id'])) {
                    throw new \InvalidArgumentException('No upload_id provided.');
                }
                $request->addPost('upload_id', $options['upload_id']);
                // Set video_result if provided.
                if (isset($options['video_result'])) {
                    $request->addPost('video_result', $options['video_result']);
                }
                break;
            case 'links':
                $request = $ig->request('direct_v2/threads/broadcast/link/');
                // Check and set link_urls.
                if (!isset($options['link_urls'])) {
                    throw new \InvalidArgumentException('No link_urls provided.');
                }
                $request->addPost('link_urls', $options['link_urls']);
                // Check and set link_text.
                if (!isset($options['link_text'])) {
                    throw new \InvalidArgumentException('No link_text provided.');
                }
                $request->addPost('link_text', $options['link_text']);
                break;
            case 'reaction':
                $request = $ig->request('direct_v2/threads/broadcast/reaction/');
                // Check and set reaction_type.
                if (!isset($options['reaction_type'])) {
                    throw new \InvalidArgumentException('No reaction_type provided.');
                }
                $request->addPost('reaction_type', $options['reaction_type']);
                // Check and set reaction_status.
                if (!isset($options['reaction_status'])) {
                    throw new \InvalidArgumentException('No reaction_status provided.');
                }
                $request->addPost('reaction_status', $options['reaction_status']);
                // Check and set item_id.
                if (!isset($options['item_id'])) {
                    throw new \InvalidArgumentException('No item_id provided.');
                }
                $request->addPost('item_id', $options['item_id']);
                // Check and set node_type.
                if (!isset($options['node_type'])) {
                    throw new \InvalidArgumentException('No node_type provided.');
                }
                $request->addPost('node_type', $options['node_type']);
                break;
            case 'story_share':
                $signedPost = true; // This must be a signed post!
                $request = $ig->request('direct_v2/threads/broadcast/story_share/');
                // Check and set story_media_id.
                if (!isset($options['story_media_id'])) {
                    throw new \InvalidArgumentException('No story_media_id provided.');
                }
                $request->addPost('story_media_id', $options['story_media_id']);
                // Set text if provided.
                if (isset($options['text']) && strlen($options['text'])) {
                    $request->addPost('text', $options['text']);
                }
                // Check and set media_type.
                if (isset($options['media_type']) && $options['media_type'] === 'video') {
                    $request->addParam('media_type', 'video');
                } else {
                    $request->addParam('media_type', 'photo');
                }
                break;
            default:
                throw new \InvalidArgumentException('Unsupported _sendDirectItem() type.');
        }
        // Add recipients.
        $recipients = $this->_prepareRecipients($recipients, false);
        if (isset($recipients['users'])) {
            $request->addPost('recipient_users', $recipients['users']);
        } elseif (isset($recipients['thread'])) {
            $request->addPost('thread_ids', $recipients['thread']);
        } else {
            throw new \InvalidArgumentException('Please provide at least one recipient.');
        }
        // Handle client_context.
        if (!isset($options['client_context'])) {
            // WARNING: Must be random every time otherwise we can only
            // make a single post per direct-discussion thread.
            $options['client_context'] = Signatures::generateUUID(true);
        } elseif (!Signatures::isValidUUID($options['client_context'])) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid UUID.', $options['client_context']));
        }
        // Add some additional data if signed post.
        if ($signedPost) {
            $request->addPost('_uid', $ig->account_id);
        }
        // Execute the request with all data used by both signed and unsigned.
        return $request->setSignedPost($signedPost)
            ->addPost('action', 'send_item')
            ->addPost('client_context', $options['client_context'])
            ->addPost('_csrftoken', $ig->client->getToken())
            ->addPost('_uuid', $ig->uuid)
            ->getResponse(new Response\DirectSendItemResponse());
    }

    protected function _prepareRecipients(
        array $recipients,
        $useQuotes)
    {
        $result = [];
        if (isset($recipients['users'])) {
            if (!is_array($recipients['users'])) {
                throw new \InvalidArgumentException('"users" must be an array.');
            }
            foreach ($recipients['users'] as $userId) {
                if (!is_scalar($userId)) {
                    throw new \InvalidArgumentException('User identifier must be scalar.');
                } elseif (!ctype_digit($userId) && (!is_int($userId) || $userId < 0)) {
                    throw new \InvalidArgumentException(sprintf('"%s" is not a valid user identifier.', $userId));
                }
            }
            // Although this is an array of groups, you will get "Only one group is supported." error
            // if you will try to use more than one group here.
            // We can't use json_encode() here, because each user id must be a number.
            $result['users'] = '[['.implode(',', $recipients['users']).']]';
        }
        // thread
        if (isset($recipients['thread'])) {
            if (!is_scalar($recipients['thread'])) {
                throw new \InvalidArgumentException('Thread identifier must be scalar.');
            } elseif (!ctype_digit($recipients['thread']) && (!is_int($recipients['thread']) || $recipients['thread'] < 0)) {
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid thread identifier.', $recipients['thread']));
            }
            // Although this is an array, you will get "Need to specify thread ID or recipient users." error
            // if you will try to use more than one thread identifier here.
            if (!$useQuotes) {
                // We can't use json_encode() here, because thread id must be a number.
                $result['thread'] = '['.$recipients['thread'].']';
            } else {
                // We can't use json_encode() here, because thread id must be a string.
                $result['thread'] = '["'.$recipients['thread'].'"]';
            }
        }
        if (!count($result)) {
            throw new \InvalidArgumentException('Please provide at least one recipient.');
        } elseif (isset($result['thread']) && isset($result['users'])) {
            throw new \InvalidArgumentException('You can not mix "users" with "thread".');
        }
        return $result;
    }
}
