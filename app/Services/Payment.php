<?php

namespace App\Services;

use App\Services\Identity;
use App\Conversation;
use App\Customer;

class Payment{

	public static function makePayment($conversation_id){
		$conversation = Conversation::where('conversation_id', $conversation_id)->orderby('id', 'desc')->first();
		
		if( !is_null($conversation) ){
			if($conversation->authorized==1){
				$transaction = $conversation->transaction;
				$amount = $transaction->amount;
				$customer = $transaction->customer;
				$receiver_id = $transaction->reciever_id;
				$receiver = Customer::where('id', $receiver_id)->first();
				$new_amount = $customer->balance - floatval($amount);
				$customer->update(['balance' => $new_amount]);
				$receiver->balance += floatval($amount);
				$receiver->save();
				return true;
			}
		}

	}

}