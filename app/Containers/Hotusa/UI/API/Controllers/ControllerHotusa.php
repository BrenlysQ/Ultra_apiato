<?php

namespace App\Containers\Hotusa\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Hotusa\Actions\HotusaHandler;
class ControllerHotusa extends ApiController {

	function getHotelAvailability() {
		return HotusaHandler::getHotelAvailabilities();
	}

	function getHotelInformation() {
		return response()->json(HotusaHandler::hotelInformation());
	}

	function getObservation() {
		return HotusaHandler::getObservations();
	}
	function bookinformation(){
		return response()->json(HotusaHandler::bookinformation());
	}
	function cancellation_fees(){
		return response()->json(HotusaHandler::cancellation_fees());
	}
	function getBookingCancellationInformation() {
		//return HotusaHandler::getBookingCancellationInformations();
		return response()->json(HotusaHandler::getBookingCancellationInformations());
	}

	function getBundledInformation() {
		return response()->json(HotusaHandler::hotelInformation());
	}

	function getProvince() {
		return HotusaHandler::getProvinces();
	}

	function getCountry() {
		return HotusaHandler::getCountries();
	}

	function getDirectory() {
		return HotusaHandler::getDirectories();
	}

	function getHotelList() {
		return HotusaHandler::getHotelsList();
	}

	function getReservation() {
		return HotusaHandler::getReservations();
	}
	function cancel_reservation(){
		return response()->json(HotusaHandler::cancel_reservation());
	}
	function cancelBooking() {
		return HotusaHandler::cancelBookings();
	}

	function confirmReservation() {
		return HotusaHandler::confirmBooking();
	}

	function getBookingInfo() {
		return HotusaHandler::infoBooking();
	}

	function cardInformation() {
		return HotusaHandler::cardsInformation();
	}

	function getBookingBonusInfo() {
		return HotusaHandler::bookingBonusInfo();
	}

}
