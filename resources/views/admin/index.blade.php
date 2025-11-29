@extends('admin.admin_master')
@section('title', 'Quiz Dashboard')
@section('admin')


        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Hospital Management System</h1>
                <p>Complete Electronic Medical Records & Healthcare Management Platform</p>
                <span class="role-badge">
                    <i class="bi bi-shield-check me-1"></i> Super Admin Access
                </span>
            </div>
            
            <!-- Real-Time Stats Section -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <h6><i class="bi bi-people me-1"></i> Total Patients Today</h6>
                        <h3>187</h3>
                        <span class="badge bg-success">+24 from yesterday</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <h6><i class="bi bi-clock-history me-1"></i> Waiting in Queue</h6>
                        <h3>34</h3>
                        <span class="badge bg-warning text-dark">Across all departments</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <h6><i class="bi bi-cash-coin me-1"></i> Today's Revenue</h6>
                        <h3>₦2.4M</h3>
                        <span class="badge bg-primary">89% collected</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card">
                        <h6><i class="bi bi-hospital me-1"></i> Bed Occupancy</h6>
                        <h3>82/120</h3>
                        <span class="badge bg-info">68% occupied</span>
                    </div>
                </div>
            </div>

            <!-- Department Queue Overview -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="queue-widget">
                        <h5><i class="bi bi-list-check me-2"></i>Live Department Queues</h5>
                        <div class="row">
                            <div class="col-md-3 col-6 mb-2">
                                <div class="queue-item">
                                    <span>Records Dept</span>
                                    <span class="queue-count">8</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="queue-item">
                                    <span>Cashier</span>
                                    <span class="queue-count">12</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="queue-item">
                                    <span>Triage</span>
                                    <span class="queue-count">6</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="queue-item">
                                    <span>Doctor Queue</span>
                                    <span class="queue-count">8</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 1. RECORD MODULE (Front Desk) -->
            <div class="section-header">
                <h4><i class="bi bi-folder-fill me-2"></i>Record Module</h4>
                <p>Front Desk & Records Officer - Patient Registration & File Management</p>
            </div>
            
    <div class="row g-3 g-lg-4 mb-4">
        <!-- Register Patient -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.create') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h5 class="tile-title">Register Patient</h5>
                <p class="tile-description">Add new patient</p>
            </a>
        </div>

        <!-- All Patients -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.all') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="tile-title">All Patients</h5>
                <p class="tile-description">View all records</p>
            </a>
        </div>

        <!-- Search Patient -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.search') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h5 class="tile-title">Search Patient</h5>
                <p class="tile-description">Find patient records</p>
            </a>
        </div>

        <!-- Open Records -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.open') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-folder-open"></i>
                </div>
                <h5 class="tile-title">Open Records</h5>
                <p class="tile-description">View patient files</p>
            </a>
        </div>

        <!-- Update Demographics -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.update-demographics') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <h5 class="tile-title">Update Demographics</h5>
                <p class="tile-description">Edit patient info</p>
            </a>
        </div>

        <!-- Visit History -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.visit-history') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5 class="tile-title">Visit History</h5>
                <p class="tile-description">Patient visits log</p>
            </a>
        </div>

        <!-- Print Patient Card -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.print-cards') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <h5 class="tile-title">Print Patient Card</h5>
                <p class="tile-description">Generate ID cards</p>
            </a>
        </div>

        <!-- Patient Reports -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.reports') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <h5 class="tile-title">Patient Reports</h5>
                <p class="tile-description">Statistics & analytics</p>
            </a>
        </div>
    </div>

            <!-- 2. ACCOUNTANT / CASHIER MODULE -->
            <div class="section-header">
                <h4><i class="bi bi-cash-stack me-2"></i>Accountant / Cashier Module</h4>
                <p>Financial Management - Billing, Payments & Receipts</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <span class="tile-badge">12</span>
                        <div class="tile-icon">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <h5 class="tile-title">Pull from Records</h5>
                        <p class="tile-description">Receive patients</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h5 class="tile-title">Generate Invoice</h5>
                        <p class="tile-description">Create bills</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h5 class="tile-title">Accept Payment</h5>
                        <p class="tile-description">Cash/POS/Transfer</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-printer"></i>
                        </div>
                        <h5 class="tile-title">Issue Receipt</h5>
                        <p class="tile-description">Print receipts</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="tile-title">HMO Billing</h5>
                        <p class="tile-description">Insurance claims</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                        <h5 class="tile-title">Manage Refunds</h5>
                        <p class="tile-description">Process refunds</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h5 class="tile-title">Outstanding Balance</h5>
                        <p class="tile-description">Unpaid services</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="tile-title">Financial Reports</h5>
                        <p class="tile-description">Revenue analytics</p>
                    </a>
                </div>
            </div>

            <!-- 3. TRIAGE MODULE (Nurses) -->
            <div class="section-header">
                <h4><i class="bi bi-heart-pulse me-2"></i>Triage Module</h4>
                <p>Nursing Triage - Vital Signs & Initial Assessment</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <span class="tile-badge">6</span>
                        <div class="tile-icon">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <h5 class="tile-title">Triage Queue</h5>
                        <p class="tile-description">Waiting list</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-thermometer-half"></i>
                        </div>
                        <h5 class="tile-title">Capture Vitals</h5>
                        <p class="tile-description">BP, Temp, Pulse, SpO₂</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-clipboard-pulse"></i>
                        </div>
                        <h5 class="tile-title">Triage Assessment</h5>
                        <p class="tile-description">Complaints & notes</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <h5 class="tile-title">Priority Level</h5>
                        <p class="tile-description">Critical/Moderate/Mild</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-arrow-right-circle"></i>
                        </div>
                        <h5 class="tile-title">Forward to Doctor</h5>
                        <p class="tile-description">Send to consultation</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <h5 class="tile-title">Emergency Transfer</h5>
                        <p class="tile-description">Send to A&E</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h5 class="tile-title">Triage Reports</h5>
                        <p class="tile-description">Daily summary</p>
                    </a>
                </div>
            </div>

            <!-- 4. DOCTOR MODULE -->
            <div class="section-header">
                <h4><i class="bi bi-person-badge me-2"></i>Doctor Module</h4>
                <p>Clinical Consultation - Diagnosis, Prescriptions & Orders</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <span class="tile-badge">8</span>
                        <div class="tile-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="tile-title">Patient Queue</h5>
                        <p class="tile-description">Waiting patients</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-journal-medical"></i>
                        </div>
                        <h5 class="tile-title">Consultation Workspace</h5>
                        <p class="tile-description">Patient records</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-card-text"></i>
                        </div>
                        <h5 class="tile-title">SOAP Notes</h5>
                        <p class="tile-description">Clinical documentation</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-prescription2"></i>
                        </div>
                        <h5 class="tile-title">E-Prescriptions</h5>
                        <p class="tile-description">Prescribe medications</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <h5 class="tile-title">Lab Requests</h5>
                        <p class="tile-description">Order investigations</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-x-diamond"></i>
                        </div>
                        <h5 class="tile-title">Imaging Requests</h5>
                        <p class="tile-description">X-ray, CT, MRI</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h5 class="tile-title">Medical Certificates</h5>
                        <p class="tile-description">Sick leave, fit notes</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h5 class="tile-title">Admission Orders</h5>
                        <p class="tile-description">Ward admission</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <h5 class="tile-title">Discharge Orders</h5>
                        <p class="tile-description">Patient discharge</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-arrow-return-right"></i>
                        </div>
                        <h5 class="tile-title">Referral Letters</h5>
                        <p class="tile-description">Specialist referral</p>
                    </a>
                </div>
            </div>

            <!-- 5. NURSE MODULE (Clinical Nursing) -->
            <div class="section-header">
                <h4><i class="bi bi-clipboard2-heart me-2"></i>Nurse Module</h4>
                <p>Clinical Nursing - Medication Administration & Ward Care</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h5 class="tile-title">Nursing Notes</h5>
                        <p class="tile-description">Patient observations</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-capsule-pill"></i>
                        </div>
                        <h5 class="tile-title">Medication Admin (MAR)</h5>
                        <p class="tile-description">Drug administration</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-droplet"></i>
                        </div>
                        <h5 class="tile-title">IV Fluid Tracking</h5>
                        <p class="tile-description">Fluid monitoring</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-bandaid"></i>
                        </div>
                        <h5 class="tile-title">Procedures</h5>
                        <p class="tile-description">Wound care, catheter</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-bed"></i>
                        </div>
                        <h5 class="tile-title">Bed Management</h5>
                        <p class="tile-description">Allocation & transfer</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h5 class="tile-title">Intake & Output Chart</h5>
                        <p class="tile-description">Fluid balance</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-file-medical"></i>
                        </div>
                        <h5 class="tile-title">Nursing Discharge</h5>
                        <p class="tile-description">Summary report</p>
                    </a>
                </div>
            </div>

            <!-- 6. PHARMACY MODULE -->
            <div class="section-header">
                <h4><i class="bi bi-capsule me-2"></i>Pharmacy Module</h4>
                <p>Drug Management - Dispensing, Inventory & Sales</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-list-task"></i>
                        </div>
                        <h5 class="tile-title">Prescription Queue</h5>
                        <p class="tile-description">Pending prescriptions</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h5 class="tile-title">Drug Dispensing</h5>
                        <p class="tile-description">Dispense medications</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <h5 class="tile-title">Drug Inventory</h5>
                        <p class="tile-description">Stock management</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h5 class="tile-title">Low Stock Alerts</h5>
                        <p class="tile-description">Reorder notifications</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h5 class="tile-title">Expiry Tracking</h5>
                        <p class="tile-description">Expiring drugs</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <h5 class="tile-title">Purchase Orders</h5>
                        <p class="tile-description">Procurement</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h5 class="tile-title">OTC Sales</h5>
                        <p class="tile-description">Over-the-counter</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <h5 class="tile-title">Pharmacy Reports</h5>
                        <p class="tile-description">Sales & stock reports</p>
                    </a>
                </div>
            </div>

            <!-- 7. LABORATORY MODULE -->
            <div class="section-header">
                <h4><i class="bi bi-clipboard2-pulse me-2"></i>Laboratory Module</h4>
                <p>Diagnostics - Lab Tests, Results & Sample Management</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h5 class="tile-title">Lab Requests</h5>
                        <p class="tile-description">Incoming orders</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <h5 class="tile-title">Sample Collection</h5>
                        <p class="tile-description">Specimen tracking</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <h5 class="tile-title">Generate Barcode</h5>
                        <p class="tile-description">Sample labeling</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <h5 class="tile-title">Results Processing</h5>
                        <p class="tile-description">Enter results</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-check-all"></i>
                        </div>
                        <h5 class="tile-title">Approve Results</h5>
                        <p class="tile-description">Quality control</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-printer"></i>
                        </div>
                        <h5 class="tile-title">Print Results</h5>
                        <p class="tile-description">Lab reports</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="tile-title">Reagent Management</h5>
                        <p class="tile-description">Lab inventory</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="tile-title">Lab Reports</h5>
                        <p class="tile-description">Test analytics</p>
                    </a>
                </div>
            </div>

            <!-- 8. RADIOLOGY / IMAGING MODULE -->
            <div class="section-header">
                <h4><i class="bi bi-x-diamond me-2"></i>Radiology / Imaging Module</h4>
                <p>Medical Imaging - X-ray, CT, MRI, Ultrasound</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h5 class="tile-title">Imaging Requests</h5>
                        <p class="tile-description">Pending orders</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h5 class="tile-title">Assign Radiographer</h5>
                        <p class="tile-description">Staff allocation</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h5 class="tile-title">Capture Images</h5>
                        <p class="tile-description">Perform scan</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-upload"></i>
                        </div>
                        <h5 class="tile-title">Upload Images</h5>
                        <p class="tile-description">DICOM/PNG/JPG</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <h5 class="tile-title">Radiologist Report</h5>
                        <p class="tile-description">Write findings</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h5 class="tile-title">Compare Images</h5>
                        <p class="tile-description">Previous scans</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-send-check"></i>
                        </div>
                        <h5 class="tile-title">Release Results</h5>
                        <p class="tile-description">Send to doctor</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5 class="tile-title">Imaging Reports</h5>
                        <p class="tile-description">Turnaround time</p>
                    </a>
                </div>
            </div>

            <!-- 9. ROLE-BASED ACCESS CONTROL (RBAC) -->
            <div class="section-header">
                <h4><i class="bi bi-shield-lock me-2"></i>System Administration & RBAC</h4>
                <p>User Management, Roles & Permissions Control</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="tile-title">User Management</h5>
                        <p class="tile-description">Staff accounts</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="tile-title">Role Assignment</h5>
                        <p class="tile-description">Define user roles</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-key"></i>
                        </div>
                        <h5 class="tile-title">Permissions Control</h5>
                        <p class="tile-description">Access rights</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="tile-title">Department Assignment</h5>
                        <p class="tile-description">Module visibility</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <h5 class="tile-title">Audit Logs</h5>
                        <p class="tile-description">System activity</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h5 class="tile-title">System Settings</h5>
                        <p class="tile-description">Configuration</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="tile-title">Security Settings</h5>
                        <p class="tile-description">Login policies</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-database"></i>
                        </div>
                        <h5 class="tile-title">Database Backup</h5>
                        <p class="tile-description">System backup</p>
                    </a>
                </div>
            </div>

            <!-- Additional Hospital Modules -->
            <div class="section-header">
                <h4><i class="bi bi-grid-3x3-gap me-2"></i>Additional Hospital Operations</h4>
                <p>Supporting Modules for Complete Hospital Management</p>
            </div>
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <h5 class="tile-title">Appointments</h5>
                        <p class="tile-description">Schedule management</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h5 class="tile-title">Ward Management</h5>
                        <p class="tile-description">Inpatient care</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h5 class="tile-title">Emergency (A&E)</h5>
                        <p class="tile-description">Emergency services</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-scissors"></i>
                        </div>
                        <h5 class="tile-title">Theatre / Surgery</h5>
                        <p class="tile-description">Operating theatre</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-gender-female"></i>
                        </div>
                        <h5 class="tile-title">Maternity / ANC</h5>
                        <p class="tile-description">Antenatal care</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-shield-plus"></i>
                        </div>
                        <h5 class="tile-title">Immunization</h5>
                        <p class="tile-description">Vaccination records</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h5 class="tile-title">HR Management</h5>
                        <p class="tile-description">Staff records</p>
                    </a>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="#" class="dashboard-tile" tabindex="0">
                        <div class="tile-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <h5 class="tile-title">Reports & Analytics</h5>
                        <p class="tile-description">System reports</p>
                    </a>
                </div>
            </div>
        </div>

@endsection