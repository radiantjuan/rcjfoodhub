<x-admin.layout>
    <div class="container">
        <input type="hidden" id="auth_token" value="{{ $auth_token }}">
        <div class="row">
            <div class="col-5">
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h5 class="m-0">Search top 10 purchased supplies</h5>
                    </div>
                    <div class="card-body cart-items">
                        <div class="row">
                            <div class="form-group col">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date"
                                       value="{{ $start_date }}"/>
                                <div class="invalid-feedback start_date_feedback">
                                </div>
                            </div>
                            <div class="form-group col">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date"
                                       value="{{ $end_date }}"/>
                                <div class="invalid-feedback end_date_feedback"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 js-submit-date-top-ten">Submit</button>
                        <hr/>
                        <div class="js-top-ten-supplies mt-2">
                            Loading...
                        </div>
                        <div class="mb-2 media d-none js-top-supplies-container-copy">
                            <img
                                width="64"
                                height="64"
                                class="mr-3 js-top-supplies-img"
                                src=""
                                alt="Generic placeholder"/>
                            <div class="media-body">
                                <h5>
                                    <span class="js-top-supplies-number"></span>. <span class="js-top-supplies-title"></span>
                                </h5>
                                <div class="row">
                                    <div class="col">
                                        Overall Purchased: <span class="js-top-supplies-overall-purchased"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h5 class="m-0">Overall Supplies Ordered</h5>
                    </div>
                    <div class="card-body cart-items">
                        <div class="row">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="showAllSupplies" checked="true">
                                    <label class="form-check-label" for="showAllSupplies">
                                        Show All
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row js-overall-supplies-dates-container d-none">
                            <div class="col">
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="startDateOverallSupplies">Start Date</label>
                                        <input type="date" class="form-control" id="startDateOverallSupplies" name="start_date_overall_supplies"
                                               value="{{ $start_date }}"/>
                                        <div class="invalid-feedback start_date_overall_supplies_feedback">
                                        </div>
                                    </div>
                                    <div class="form-group col">
                                        <label for="endDateOverallSupplies">End Date</label>
                                        <input type="date" class="form-control" id="endDateOverallSupplies" name="end_date_overall_supplies"
                                               value="{{ $end_date }}"/>
                                        <div class="invalid-feedback end_date_overall_supplies_feedback"></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary w-100 js-submit-date-overall-supplies">Submit</button>
                            </div>
                        </div>

                        <hr/>
                        <table class="js-supplies-table table">
                            <thead>
                            <tr>
                                <th>Supply Item</th>
                                <th>Items Sold</th>
                                <th>Total Revenue</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
