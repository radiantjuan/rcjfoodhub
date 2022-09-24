<x-admin.layout>
    <input type="hidden" id="auth_token" value="{{ $auth_token }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card weekly report-card">
                    <div class="card-header">
                        Overall Sales Report
                    </div>
                    <div class="card-body">
{{--                        <div class="row">--}}
{{--                            <div class="form-group col-4">--}}
{{--                                <label for="Year">Choose year</label>--}}
{{--                                <select id="Year" class="form-control">--}}
{{--                                    @for($i = date('Y'); $i >= $first_year_order; $i--)--}}
{{--                                        <option>{{$i}}</option>--}}
{{--                                    @endfor--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <hr/>--}}
                        <div class="table-container">
                            <table id="SalesTable" class="table js-overallsales-table">
                                <thead>
                                    <tr>
                                        <td>Year</td>
                                        <td>Jan</td>
                                        <td>Feb</td>
                                        <td>March</td>
                                        <td>Apr</td>
                                        <td>May</td>
                                        <td>Jun</td>
                                        <td>Jul</td>
                                        <td>Aug</td>
                                        <td>Sept</td>
                                        <td>Oct</td>
                                        <td>Nov</td>
                                        <td>Dec</td>
                                        <td>Total</td>
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
    </div>
</x-admin.layout>
