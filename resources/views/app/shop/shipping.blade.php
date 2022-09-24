<x-admin.layout>
    <div class="container-fluid">
        <form method="POST" action="{{ route('save-order') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row justify-content-center">
                <div class="col-12 col-lg-4">
                    <div class="card bg-light mb-3">
                        <div class="card-header">Payment & Shipping</div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $key => $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="hidden" name="cart_items" value="" />
                            <input type="hidden" name="cart_total" value="">
                            <input type="hidden" name="promo_code_setup" value="">
                            <div class="form-group">
                                <label for="shipping_option">Shipping Option</label>
                                <select class="form-control" id="shippingOption" name="shipping_option">
                                    <option value="deliver_to_branch">Deliver To Branch</option>
                                    <option value="pickup">Pickup</option>
                                    <option value="deliver_to_other_address">Deliver To Other Address</option>
                                </select>
                            </div>
                            <div class="delivery-container d-none">
                                <div class="form-group">
                                    <label for="shipping_address_1">Address 1<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_address_1"
                                        placeholder="Enter Address Here" aria-describedby="shipping_address_1Feedback">

                                </div>
                                <div class="form-group">
                                    <label for="shipping_address_2">Address 2</label>
                                    <input type="text" class="form-control" name="shipping_address_2"
                                        placeholder="Enter Address Here" aria-describedby="shipping_address_2Feedback">

                                </div>
                                <div class="form-group">
                                    <label for="shipping_city">City<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_city"
                                        placeholder="Enter City Here" aria-describedby="shipping_cityFeedback">

                                </div>
                                <div class="form-group">
                                    <label for="shipping_barangay">Barangay<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="shipping_barangay"
                                        placeholder="Enter Barangay Name Here"
                                        aria-describedby="shipping_barangayFeedback">
                                </div>
                                <div class="form-group">
                                    <label for="shipping_zip_code">Zip Code<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="shipping_zip_code"
                                        placeholder="Enter Zip Code" aria-describedby="shipping_zip_codeFeedback">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Payment Option</label>
                                <select class="form-control" id="paymentOption" name="payment_method">
                                    <option value="bank_transfer">Online Transfer</option>
                                    <option value="pay_later">Pay Later</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="special_instructions">Special Instructions</label>
                                <textarea class="form-control" id="special_instructions" name="special_instructions" rows="3"></textarea>
                            </div>
                            <div class="payment-upload">
                                <div class="form-group">
                                    <label for="proof_of_payment">Upload Proof Of Payment <span class="text-danger">*</span></label>
                                    <input type="file" name="proof_of_payment" id="">
                                </div>
                                <div class="alert alert-info">
                                    <ul style="list-style:none; margin:0; padding:0;">
                                        <li><strong>Bank:</strong> BPI</li>     
                                        <li><strong>Account Name:</strong> Juan Eduardo Sibug</li>     
                                        <li><strong>Account Number:</strong> 3649-1846-86 </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="payment_transaction_number">Payment Transaction Number: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="payment_transaction_number"
                                        placeholder="Enter Transaction" aria-describedby="shipping_zip_codeFeedback">
                                </div>
                            </div>
                            <div class="alert alert-warning alert-pay-later d-none">
                                Payment shall be uploaded the following day or else orders will not be valid
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg mt-3 btn-success w-100">Place Order</button>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            Review Order
                        </div>
                        <div class="card-body cart-items">
                            {{-- JS POPULATED --}}
                        </div>
                        <div class="card-footer">
                            <div class="cart-total">
                                {{-- JS POPULATED --}}
                            </div>
                            <hr>
                            <div class="promo-code-setup">
                                {{-- JS POPULATED --}}
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <div class="m-0 card-title h3">Total to be paid: <span class="total-to-be-paid">
                                            {{-- JS POPULATER --}}
                                        </span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form>
    </div>
</x-admin.layout>
