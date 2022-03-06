<?php 
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'buyer','middleware'=>['auth','inactiveuser','UserRoleCheck']],function(){

    Route::get('/dashboard', 'Frontend\BuyerController@buyerDashboard')->name('buyer.dashboard');
    Route::get('/profile','Frontend\BuyerController@buyerProfile')->name('buyer.profile');
    Route::match(['get','post'],'/profile-edit','Frontend\BuyerController@buyerProfileEdit')->name('buyer.profile.edit');
    Route::match(['get','post'],'/account-settings','Frontend\BuyerController@buyerAccountSetting')->name('buyer.account.settings');
    Route::get('/logout', 'Frontend\BuyerController@buyerLogout')->name('buyer.logout');

     //all orders 
     Route::get('/orders','Frontend\BuyerController@buyerOrders')->name('buyer.orders');
     Route::get('/orders-details/{id}','Frontend\BuyerController@orderDetails')->name('buyer.order.details');
     Route::post('/order-status-change','Frontend\BuyerController@orderStatus')->name('buyer.order.status');

     Route::get('order-invoice-details/{id?}','Frontend\InvoiceController@orderInvoiceBuyer')->name('buyer.order.invoice.details');

     //tickets
    Route::get('all-tickets','Frontend\BuyerController@allTickets')->name('buyer.support.ticket');
    Route::match(['get','post'],'add-new-ticket/{id?}','Frontend\BuyerController@addNewTicket')->name('buyer.support.ticket.new');
    Route::post('support-ticket-delete/{id}','Frontend\BuyerController@ticketDelete')->name('buyer.support.ticket.delete');
    Route::post('support-ticket/priority-change/','Frontend\BuyerController@priorityChange')->name('buyer.support.ticket.priority.change');
    Route::get('support-ticket/status-change/{id?}','Frontend\BuyerController@statusChange')->name('buyer.support.ticket.status.change');
    Route::get('ticket-view/{id}','Frontend\BuyerController@view_ticket')->name('buyer.support.ticket.view');
    Route::post('support-ticket/message-send', 'Frontend\BuyerController@support_ticket_message')->name('buyer.support.ticket.message.send');

});