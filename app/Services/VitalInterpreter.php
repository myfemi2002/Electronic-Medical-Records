<?php

namespace App\Services;

use App\Models\TriageVital;

class VitalInterpreter
{
    /**
     * Analyze all vitals and generate interpretations
     * Based on WHO, AHA/ACC, CDC/NIH guidelines
     */
    public static function analyze(TriageVital $vital)
    {
        $interpretations = [];
        $alerts = [];
        $priorities = [];

        // Calculate BMI if not already done
        if (!$vital->bmi && $vital->weight && $vital->height) {
            $vital->calculateBMI();
        }

        // 1. BLOOD PRESSURE INTERPRETATION (AHA Standard)
        if ($vital->blood_pressure) {
            $bpResult = self::interpretBloodPressure($vital->blood_pressure);
            $interpretations['bp'] = $bpResult['interpretation'];
            $priorities[] = $bpResult['priority'];
            if ($bpResult['alert']) {
                $alerts[] = $bpResult['alert'];
            }
        }

        // 2. TEMPERATURE INTERPRETATION (WHO/CDC)
        if ($vital->temperature) {
            $tempResult = self::interpretTemperature($vital->temperature);
            $interpretations['temp'] = $tempResult['interpretation'];
            $priorities[] = $tempResult['priority'];
            if ($tempResult['alert']) {
                $alerts[] = $tempResult['alert'];
            }
        }

        // 3. PULSE RATE INTERPRETATION (NIH)
        if ($vital->pulse_rate) {
            $pulseResult = self::interpretPulseRate($vital->pulse_rate);
            $interpretations['pulse'] = $pulseResult['interpretation'];
            $priorities[] = $pulseResult['priority'];
            if ($pulseResult['alert']) {
                $alerts[] = $pulseResult['alert'];
            }
        }

        // 4. RESPIRATORY RATE INTERPRETATION (CDC)
        if ($vital->respiratory_rate) {
            $rrResult = self::interpretRespiratoryRate($vital->respiratory_rate);
            $interpretations['rr'] = $rrResult['interpretation'];
            $priorities[] = $rrResult['priority'];
            if ($rrResult['alert']) {
                $alerts[] = $rrResult['alert'];
            }
        }

        // 5. OXYGEN SATURATION INTERPRETATION
        if ($vital->oxygen_saturation) {
            $spo2Result = self::interpretOxygenSaturation($vital->oxygen_saturation);
            $interpretations['spo2'] = $spo2Result['interpretation'];
            $priorities[] = $spo2Result['priority'];
            if ($spo2Result['alert']) {
                $alerts[] = $spo2Result['alert'];
            }
        }

        // 6. BMI INTERPRETATION (WHO)
        if ($vital->bmi) {
            $bmiResult = self::interpretBMI($vital->bmi);
            $interpretations['bmi'] = $bmiResult['interpretation'];
        }

        // Determine Overall Priority
        $overallPriority = self::determineOverallPriority($priorities);

        // Generate Clinical Suggestions
        $suggestions = self::generateSuggestions($interpretations, $overallPriority, $alerts);

        return [
            'interpretations' => $interpretations,
            'alerts' => $alerts,
            'overall_priority' => $overallPriority,
            'suggestions' => $suggestions,
        ];
    }

    /**
     * BLOOD PRESSURE INTERPRETATION (AHA Standard)
     */
    private static function interpretBloodPressure($bp)
    {
        $parts = explode('/', $bp);
        $systolic = (int) ($parts[0] ?? 0);
        $diastolic = (int) ($parts[1] ?? 0);

        // Hypertensive Crisis
        if ($systolic >= 180 || $diastolic >= 120) {
            return [
                'interpretation' => "Hypertensive Crisis ({$bp}) - IMMEDIATE medical attention required",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Hypertensive crisis detected. Risk of stroke, heart attack, or organ damage. Immediate emergency intervention required.',
            ];
        }

        // Stage 2 Hypertension
        if ($systolic >= 160 || $diastolic >= 100) {
            return [
                'interpretation' => "Stage 2 Hypertension ({$bp}) - High blood pressure, requires immediate doctor review",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Stage 2 Hypertension detected. Immediate doctor consultation required.',
            ];
        }

        // Stage 1 Hypertension
        if ($systolic >= 140 || $diastolic >= 90) {
            return [
                'interpretation' => "Stage 1 Hypertension ({$bp}) - Elevated blood pressure, doctor review recommended",
                'priority' => 'moderate',
                'alert' => 'Moderate: Elevated blood pressure detected. Doctor consultation recommended.',
            ];
        }

        // Pre-hypertension
        if ($systolic >= 121 || $diastolic >= 81) {
            return [
                'interpretation' => "Pre-hypertension ({$bp}) - Blood pressure slightly elevated, monitor closely",
                'priority' => 'mild',
                'alert' => null,
            ];
        }

        // Hypotension (Low)
        if ($systolic < 90 || $diastolic < 60) {
            return [
                'interpretation' => "Hypotension ({$bp}) - Low blood pressure, assess for shock or dehydration",
                'priority' => 'moderate',
                'alert' => 'Moderate: Low blood pressure detected. Assess for signs of shock, dehydration, or underlying condition.',
            ];
        }

        // Normal
        return [
            'interpretation' => "Normal blood pressure ({$bp})",
            'priority' => 'mild',
            'alert' => null,
        ];
    }

    /**
     * TEMPERATURE INTERPRETATION (WHO/CDC)
     */
    private static function interpretTemperature($temp)
    {
        // Hyperpyrexia (Extreme fever)
        if ($temp > 39.0) {
            return [
                'interpretation' => "Hyperpyrexia ({$temp}°C) - Extreme fever requiring immediate attention",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Hyperpyrexia detected (>39°C). Risk of febrile seizures, dehydration, or serious infection. Immediate cooling measures and investigation required.',
            ];
        }

        // Fever
        if ($temp >= 38.1) {
            return [
                'interpretation' => "Fever ({$temp}°C) - Elevated temperature, assess for infection",
                'priority' => 'moderate',
                'alert' => 'Moderate: Fever detected. Assess for source of infection, provide antipyretics if needed.',
            ];
        }

        // Low-grade fever
        if ($temp >= 37.5) {
            return [
                'interpretation' => "Low-grade fever ({$temp}°C) - Slightly elevated temperature, monitor",
                'priority' => 'mild',
                'alert' => null,
            ];
        }

        // Hypothermia
        if ($temp < 35.0) {
            return [
                'interpretation' => "Hypothermia ({$temp}°C) - Body temperature dangerously low",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Hypothermia detected (<35°C). Immediate warming measures required. Assess for environmental exposure or underlying condition.',
            ];
        }

        // Normal
        return [
            'interpretation' => "Normal temperature ({$temp}°C)",
            'priority' => 'mild',
            'alert' => null,
        ];
    }

    /**
     * PULSE RATE INTERPRETATION (NIH)
     */
    private static function interpretPulseRate($pulse)
    {
        // Severe Tachycardia
        if ($pulse > 130) {
            return [
                'interpretation' => "Severe Tachycardia ({$pulse} bpm) - Dangerously fast heart rate",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Severe tachycardia detected (>130 bpm). Risk of cardiac complications. Immediate ECG and doctor review required.',
            ];
        }

        // Tachycardia
        if ($pulse > 100) {
            return [
                'interpretation' => "Tachycardia ({$pulse} bpm) - Elevated heart rate, investigate cause",
                'priority' => 'moderate',
                'alert' => 'Moderate: Tachycardia detected. Assess for fever, pain, anxiety, dehydration, or cardiac issues.',
            ];
        }

        // Severe Bradycardia
        if ($pulse < 40) {
            return [
                'interpretation' => "Severe Bradycardia ({$pulse} bpm) - Dangerously slow heart rate",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Severe bradycardia detected (<40 bpm). Risk of inadequate cardiac output. Immediate ECG and doctor review required.',
            ];
        }

        // Bradycardia
        if ($pulse < 60) {
            return [
                'interpretation' => "Bradycardia ({$pulse} bpm) - Slow heart rate, assess clinical significance",
                'priority' => 'moderate',
                'alert' => 'Moderate: Bradycardia detected. Assess if patient is athletic or if medications/conditions are causing low heart rate.',
            ];
        }

        // Normal
        return [
            'interpretation' => "Normal pulse rate ({$pulse} bpm)",
            'priority' => 'mild',
            'alert' => null,
        ];
    }

    /**
     * RESPIRATORY RATE INTERPRETATION (CDC)
     */
    private static function interpretRespiratoryRate($rr)
    {
        // Severe Tachypnea
        if ($rr > 24) {
            return [
                'interpretation' => "Severe Tachypnea ({$rr} breaths/min) - Rapid breathing indicating respiratory distress",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Severe tachypnea detected (>24/min). Possible respiratory distress, pneumonia, pulmonary embolism, or metabolic acidosis. Immediate assessment required.',
            ];
        }

        // Mild Tachypnea
        if ($rr > 20) {
            return [
                'interpretation' => "Mild Tachypnea ({$rr} breaths/min) - Slightly elevated respiratory rate",
                'priority' => 'moderate',
                'alert' => 'Moderate: Elevated respiratory rate detected. Assess for pain, anxiety, fever, or early respiratory compromise.',
            ];
        }

        // Bradypnea
        if ($rr < 12) {
            return [
                'interpretation' => "Bradypnea ({$rr} breaths/min) - Abnormally slow breathing",
                'priority' => 'moderate',
                'alert' => 'Moderate: Bradypnea detected. Assess for CNS depression, medication effects, or metabolic abnormalities.',
            ];
        }

        // Normal
        return [
            'interpretation' => "Normal respiratory rate ({$rr} breaths/min)",
            'priority' => 'mild',
            'alert' => null,
        ];
    }

    /**
     * OXYGEN SATURATION INTERPRETATION
     */
    private static function interpretOxygenSaturation($spo2)
    {
        // Severe Hypoxia
        if ($spo2 < 90) {
            return [
                'interpretation' => "Severe Hypoxia (SpO₂: {$spo2}%) - Critically low oxygen levels",
                'priority' => 'critical',
                'alert' => 'CRITICAL: Severe hypoxia detected (SpO₂ <90%). Immediate oxygen therapy required. Assess for respiratory failure, cardiac issues, or severe pneumonia.',
            ];
        }

        // Mild Hypoxia
        if ($spo2 < 95) {
            return [
                'interpretation' => "Mild Hypoxia (SpO₂: {$spo2}%) - Oxygen levels below normal",
                'priority' => 'moderate',
                'alert' => 'Moderate: Mild hypoxia detected (SpO₂ 90-94%). Consider supplemental oxygen. Investigate underlying cause.',
            ];
        }

        // Normal
        return [
            'interpretation' => "Normal oxygen saturation (SpO₂: {$spo2}%)",
            'priority' => 'mild',
            'alert' => null,
        ];
    }

    /**
     * BMI INTERPRETATION (WHO)
     */
    private static function interpretBMI($bmi)
    {
        if ($bmi >= 40) {
            return ['interpretation' => "Morbid Obesity (BMI: {$bmi}) - Class III Obesity"];
        }

        if ($bmi >= 35) {
            return ['interpretation' => "Severe Obesity (BMI: {$bmi}) - Class II Obesity"];
        }

        if ($bmi >= 30) {
            return ['interpretation' => "Obesity (BMI: {$bmi}) - Class I Obesity"];
        }

        if ($bmi >= 25) {
            return ['interpretation' => "Overweight (BMI: {$bmi})"];
        }

        if ($bmi >= 18.5) {
            return ['interpretation' => "Normal weight (BMI: {$bmi})"];
        }

        return ['interpretation' => "Underweight (BMI: {$bmi})"];
    }

    /**
     * Determine Overall Priority
     * Rule: ANY critical = critical, ANY moderate = moderate, else mild
     */
    private static function determineOverallPriority($priorities)
    {
        if (in_array('critical', $priorities)) {
            return 'critical';
        }

        if (in_array('moderate', $priorities)) {
            return 'moderate';
        }

        return 'mild';
    }

    /**
     * Generate Clinical Suggestions based on findings
     */
    private static function generateSuggestions($interpretations, $priority, $alerts)
    {
        $suggestions = [];

        if ($priority === 'critical') {
            $suggestions[] = "⚠️ URGENT: Patient requires IMMEDIATE medical attention";
            $suggestions[] = "Consider emergency transfer if needed";
            $suggestions[] = "Continuous monitoring of vital signs required";
        } elseif ($priority === 'moderate') {
            $suggestions[] = "Doctor review recommended";
            $suggestions[] = "Monitor vital signs regularly";
            $suggestions[] = "Investigate underlying causes";
        } else {
            $suggestions[] = "Vitals within acceptable range";
            $suggestions[] = "Continue routine assessment";
        }

        // Add specific suggestions based on alerts
        foreach ($alerts as $alert) {
            if (strpos($alert, 'hypoxia') !== false) {
                $suggestions[] = "Administer oxygen therapy";
                $suggestions[] = "Perform arterial blood gas analysis";
            }
            
            if (strpos($alert, 'Hypertensive') !== false) {
                $suggestions[] = "Check blood pressure medication history";
                $suggestions[] = "Assess for signs of end-organ damage";
            }
            
            if (strpos($alert, 'Hyperpyrexia') !== false || strpos($alert, 'Fever') !== false) {
                $suggestions[] = "Administer antipyretics";
                $suggestions[] = "Investigate source of infection";
                $suggestions[] = "Send blood cultures if indicated";
            }
            
            if (strpos($alert, 'Tachycardia') !== false) {
                $suggestions[] = "Obtain 12-lead ECG";
                $suggestions[] = "Check electrolytes";
            }
            
            if (strpos($alert, 'Tachypnea') !== false) {
                $suggestions[] = "Assess respiratory pattern and effort";
                $suggestions[] = "Consider chest X-ray";
            }
        }

        return $suggestions;
    }
}