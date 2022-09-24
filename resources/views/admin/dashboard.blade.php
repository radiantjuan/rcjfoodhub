<x-admin.layout>
    <div class="container">
        <div class="card w-100">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4>Announcements</h4>
                    </div>
                    <div class="col-6 text-right">
                       <div class="row justify-content-end align-items-center px-3">
                            <form action="" method="GET" class="mr-3 row justify-content-end">
                                <input type="text" name="searchq" class="form-control form-control-sm w-75 mr-1">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                            </form>
                            @if (!empty($_GET['start_date']) && !empty($_GET['end_date']) || !empty($_GET['searchq']))
                                <a href="{{route('admin.dashboard')}}" class="btn btn-danger btn-sm mr-1"><i class="fa fa-ban"></i>
                                    Clear filter</a>
                            @endif
                            <a href="javascript:void(0)" class="btn btn-primary btn-filter btn-sm"><i class="fa fa-filter"></i>
                                Filter</a>
                       </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid announcement-container">
                    <div class="row">
                        @foreach ($announcements as $announcement)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <img src="{{ \Storage::url($announcement->img_url) }}" class="card-img-top"
                                        alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
                                        <small>{{ $announcement->date_published }}</small>
                                        <p class="card-text mt-3">{{ $announcement->excerpt }}</p>
                                        <a href="#{{ $announcement->id }}"
                                            class="js-announcement-read-more btn btn-sm btn-primary"
                                            data-id="{{ $announcement->id }}" class="card-link">Read More</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="sub-title text-muted"></h6>
                    <div class="announcement-content"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="announcementFilter" tabindex="-1" role="dialog"
        aria-labelledby="announcementFilterLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-filter"></i> Filter Announcements</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" name="start_date" required
                                    value="{{ !empty($_GET['start_date']) ? $_GET['start_date'] : '' }}">
                            </div>
                            <div class="form-group col-6">
                                <label for="start_date">End Date:</label>
                                <input type="date" class="form-control" name="end_date" required
                                    value="{{ !empty($_GET['end_date']) ? $_GET['end_date'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin.layout>
