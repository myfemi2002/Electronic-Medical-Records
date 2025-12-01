{{-- resources/views/backend/records/patients/print-card.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient ID Card - {{ $patient->card_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .print-container {
            max-width: 1200px;
            width: 100%;
        }

        .controls {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn {
            padding: 14px 32px;
            margin: 0 8px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-family: 'Inter', sans-serif;
        }

        .btn-print {
            background: white;
            color: #4f46e5;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .btn-close {
            background: rgba(255,255,255,0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-close:hover {
            background: rgba(255,255,255,0.3);
        }

        /* ID Card Container */
        .cards-wrapper {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            perspective: 1000px;
        }

        .card-container {
            position: relative;
            width: 540px;
            height: 340px;
        }

        .id-card {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            transition: all 0.3s ease;
        }

        .card-container:hover .id-card {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0,0,0,0.4);
        }

        /* FRONT CARD */
        .card-front {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .card-front::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .card-front::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .front-header {
            padding: 28px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 1;
        }

        .hospital-name {
            color: white;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .hospital-subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .patient-type-badge {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .front-body {
            padding: 28px 35px 80px 35px;
            display: flex;
            gap: 25px;
            position: relative;
            z-index: 1;
        }

        .photo-container {
            width: 130px;
            height: 130px;
            border-radius: 20px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            flex-shrink: 0;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 65px;
            color: white;
        }

        .patient-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            overflow: hidden;
        }

        .patient-name {
            font-size: 24px;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: -0.3px;
            line-height: 1.1;
            max-width: 100%;
            word-wrap: break-word;
        }

        .detail-row {
            display: flex;
            gap: 10px;
            margin-bottom: 5px;
            font-size: 13px;
            align-items: center;
        }

        .detail-label {
            color: rgba(255,255,255,0.5);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 11px;
            min-width: 60px;
        }

        .detail-value {
            color: rgba(255,255,255,0.95);
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .front-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 22px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
            z-index: 1;
        }

        .card-number-display {
            font-size: 24px;
            font-weight: 800;
            color: white;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }

        .secure-chip {
            width: 60px;
            height: 45px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(251, 191, 36, 0.4);
            position: relative;
        }

        .secure-chip::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 5px;
        }

        /* BACK CARD */
        .card-back {
            background: #ffffff;
            padding: 0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .back-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(135deg, transparent 0%, rgba(79, 70, 229, 0.02) 100%),
                repeating-linear-gradient(90deg, transparent, transparent 20px, rgba(79, 70, 229, 0.02) 20px, rgba(79, 70, 229, 0.02) 40px);
            pointer-events: none;
        }

        /* Top Header Bar */
        .back-top-bar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 20px 30px;
            position: relative;
            z-index: 1;
        }

        .back-top-bar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
        }

        .back-header {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .back-title {
            font-size: 18px;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .back-subtitle {
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Content Area */
        .back-content-area {
            padding: 20px 30px;
            position: relative;
            z-index: 1;
        }

        /* Side by Side Layout */
        .info-sections-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 18px;
        }

        .info-section {
            background: white;
            border-radius: 0;
            border-left: 4px solid #4f46e5;
            padding: 0 0 0 14px;
            box-shadow: none;
            position: relative;
            z-index: 1;
        }

        .info-title {
            font-size: 11px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            display: block;
        }

        .info-icon {
            display: none;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 7px;
        }

        .info-item {
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 10px;
            font-size: 10px;
            align-items: baseline;
        }

        .info-key {
            color: #64748b;
            font-weight: 700;
            flex-shrink: 0;
        }

        .info-value {
            color: #1e293b;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Emergency Contact - Special Styling */
        .emergency-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 14px;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.12);
        }

        .emergency-section .info-title {
            color: #b45309;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .emergency-section .info-title::before {
            content: '⚠️';
            font-size: 13px;
        }

        .emergency-section .info-item {
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 10px;
            font-size: 10px;
            align-items: start;
        }

        .emergency-section .info-key {
            color: #92400e;
        }

        .emergency-section .info-value {
            color: #1e293b;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }

        .barcode-section {
            text-align: center;
            margin-top: 0;
            position: relative;
            z-index: 1;
            background: #f8fafc;
            padding: 12px;
            border-radius: 10px;
        }

        .barcode-box {
            background: white;
            padding: 8px 14px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 2px solid #e2e8f0;
        }

        .barcode-lines {
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
        }

        .bar {
            width: 2px;
            height: 100%;
            background: #1e293b;
        }

        .bar:nth-child(odd) {
            height: 80%;
        }

        .barcode-number {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 4px;
            letter-spacing: 2px;
        }

        .validity-notice {
            text-align: center;
            font-size: 8px;
            color: #64748b;
            margin-top: 8px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            padding: 6px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-container {
                max-width: none;
            }

            .controls {
                display: none;
            }

            .cards-wrapper {
                gap: 20px;
            }

            .card-container {
                page-break-inside: avoid;
            }

            .id-card {
                box-shadow: 0 0 0 1px #e2e8f0;
            }

            @page {
                size: A4 landscape;
                margin: 15mm;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-container {
            animation: fadeIn 0.6s ease forwards;
        }

        .card-container:nth-child(2) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="controls">
            <button class="btn btn-print" onclick="window.print()">
                🖨️ Print Card
            </button>
            <button class="btn btn-close" onclick="window.close()">
                ← Back
            </button>
        </div>

        <div class="cards-wrapper">
            <!-- FRONT CARD -->
            <div class="card-container">
                <div class="id-card">
                    <div class="card-front">
                        <div class="front-header">
                            <div>
                                <div class="hospital-name">HOSPITAL</div>
                                <div class="hospital-subtitle">Health Management</div>
                            </div>
                            <div class="patient-type-badge">{{ $patient->patient_type }}</div>
                        </div>

                        <div class="front-body">
                            <div class="photo-container">
                                @if($patient->image)
                                    <img src="{{ asset($patient->image) }}" alt="Patient Photo">
                                @else
                                    <div class="photo-placeholder">👤</div>
                                @endif
                            </div>

                            <div class="patient-details">
                                <div class="patient-name">{{ $patient->full_name }}</div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Gender</span>
                                    <span class="detail-value">{{ $patient->patient_gender }}</span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">DOB</span>
                                    <span class="detail-value">{{ $patient->patient_dob->format('d M Y') }}</span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Age</span>
                                    <span class="detail-value">{{ $patient->age }} Years</span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Phone</span>
                                    <span class="detail-value">{{ $patient->patient_phone }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="front-footer">
                            <div class="card-number-display">{{ $patient->card_number }}</div>
                            <div class="secure-chip"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BACK CARD -->
            <div class="card-container">
                <div class="id-card">
                    <div class="card-back">
                        <div class="back-pattern"></div>
                        
                        <!-- Top Bar with Header -->
                        <div class="back-top-bar">
                            <div class="back-header">
                                <div class="back-title">Patient Information</div>
                                <div class="back-subtitle">Hospital Management System</div>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="back-content-area">
                            <!-- Side by Side: Patient Details & Emergency Contact -->
                            <div class="info-sections-wrapper">
                                <!-- Patient Details (Left) -->
                                <div class="info-section">
                                    <div class="info-title">Patient Details</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-key">Card No:</span>
                                            <span class="info-value">{{ $patient->card_number }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-key">Patient Type:</span>
                                            <span class="info-value">{{ $patient->patient_type }}</span>
                                        </div>
                                        @if($patient->patient_type == 'HMO' && $patient->patient_hmo)
                                        <div class="info-item">
                                            <span class="info-key">HMO:</span>
                                            <span class="info-value">{{ $patient->patient_hmo }}</span>
                                        </div>
                                        @endif
                                        <div class="info-item">
                                            <span class="info-key">Blood Type:</span>
                                            <span class="info-value">N/A</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-key">Issued:</span>
                                            <span class="info-value">{{ $patient->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Contact (Right) -->
                                <div class="emergency-section">
                                    <div class="info-title">Emergency Contact</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="info-key">Name:</span>
                                            <span class="info-value">{{ $patient->patient_kin_name ?? 'Not Provided' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-key">Relation:</span>
                                            <span class="info-value">{{ $patient->kin_relationship ?? 'Not Provided' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-key">Phone:</span>
                                            <span class="info-value">{{ $patient->patient_kin_phone ?? 'Not Provided' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barcode -->
                            <div class="barcode-section">
                                <div class="barcode-box">
                                    <div class="barcode-lines">
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                    </div>
                                    <div class="barcode-number">{{ $patient->card_number }}</div>
                                </div>
                                
                                <div class="validity-notice">
                                    Valid identification card. Please present at every hospital visit.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Smooth print dialog
        document.querySelector('.btn-print').addEventListener('click', function() {
            setTimeout(() => {
                window.print();
            }, 100);
        });

        // Optional: Auto-close after print
        window.onafterprint = function() {
            // Uncomment to enable auto-close
            // setTimeout(() => window.close(), 1000);
        };
    </script>
</body>
</html>