@extends('admin.admin_master')
@section('title', 'Quiz Dashboard')
@section('admin')

<div class="container-fluid">
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Quiz Platform Dashboard</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);" class="text-primary">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>

    <!-- Start Main Widgets -->
    <div class="row">
        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center mb-2">
                            <p class="mb-0 text-dark fs-16 fw-medium">Total Quiz Sessions</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="mb-0 fs-24 text-dark me-4">0</h3>
                            <div class="text-primary">
                                <i class="mdi mdi-file-document-box fs-24"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-success fs-14 me-2">All Sessions<i class="mdi mdi-arrow-up fs-16 ms-1"></i></span>
                            <p class="text-muted fs-13 mb-0">Created to date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center mb-2">
                            <p class="mb-0 text-dark fs-16 fw-medium">Active Sessions</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="mb-0 fs-24 text-dark me-4">0</h3>
                            <div class="text-success">
                                <i class="mdi mdi-check-circle fs-24"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-success fs-14 me-2">Currently Active<i class="mdi mdi-menu-up fs-16 ms-1"></i></span>
                            <p class="text-muted fs-13 mb-0">Students can access</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center mb-2">
                            <p class="mb-0 text-dark fs-16 fw-medium">Total Registrations</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="mb-0 fs-24 text-dark me-4">0</h3>
                            <div class="text-info">
                                <i class="mdi mdi-account-multiple fs-24"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-info fs-14 me-2">Students Registered</span>
                            <p class="text-muted fs-13 mb-0">Across all sessions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center mb-2">
                            <p class="mb-0 text-dark fs-16 fw-medium">Completed Quizzes</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="mb-0 fs-24 text-dark me-4">0</h3>
                            <div class="text-warning">
                                <i class="mdi mdi-trophy fs-24"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-warning fs-14 me-2">Quiz Submissions</span>
                            <p class="text-muted fs-13 mb-0">Successfully completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl">
            <div class="card">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center mb-2">
                            <p class="mb-0 text-dark fs-16 fw-medium">Exam Types</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="mb-0 fs-24 text-dark me-4">0</h3>
                            <div class="text-purple">
                                <i class="mdi mdi-format-list-bulleted fs-24"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-purple fs-14 me-2">Active Categories</span>
                            <p class="text-muted fs-13 mb-0">Available for quizzes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Widgets -->

    <!-- Start Row -->
    <div class="row">
        <!-- Security Overview -->
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-shield-account me-2"></i>Security Overview
                        </h5>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-eye me-1"></i>View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary mb-1">0</h4>
                                <p class="text-muted mb-0 small">Login Attempts Today</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success mb-1">0</h4>
                                <p class="text-muted mb-0 small">Failed Today</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success mb-1">0</h4>
                                <p class="text-muted mb-0 small">Suspicious IPs</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-info mb-1">0</h4>
                                <p class="text-muted mb-0 small">Failed (24h)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="mdi mdi-shield-alert me-1"></i>Security Check
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6 col-xl-6">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title text-dark mb-0">Quick Actions</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="mdi mdi-plus me-2"></i>Create New Quiz Session
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="mdi mdi-cog me-2"></i>Manage Exam Types
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="mdi mdi-view-dashboard me-2"></i>View All Sessions
                        </a>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-muted mb-3">Session Status Distribution</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="fs-14">Active</span>
                                    <span class="badge bg-success">0</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="fs-14">Total</span>
                                    <span class="badge bg-primary">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Quiz Sessions -->
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title text-dark mb-0">Recent Quiz Sessions</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="mdi mdi-file-document-box fs-48 text-muted"></i>
                        <h5 class="mt-3 text-muted">No Quiz Sessions Yet</h5>
                        <p class="text-muted">Create your first quiz session to get started!</p>
                        <a href="#" class="btn btn-primary">
                            <i class="mdi mdi-plus me-2"></i>Create Quiz Session
                        </a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <!-- End Row -->
</div>

@endsection