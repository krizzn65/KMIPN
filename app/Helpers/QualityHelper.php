<?php

namespace App\Helpers;

class QualityHelper
{
    /**
     * Determine parameter status based on thresholds
     *
     * @param string $parameter
     * @param float $value
     * @return array
     */
    public static function determineParameterStatus(string $parameter, float $value): array
    {
        $thresholds = self::getThresholds();

        if (!isset($thresholds[$parameter])) {
            return [
                'status' => 'unknown',
                'color' => 'gray',
                'icon' => 'fas fa-question-circle'
            ];
        }

        $paramThresholds = $thresholds[$parameter];

        // Check for danger status first (below normal range)
        if ($value < $paramThresholds['normal_low']) {
            if ($value < $paramThresholds['warning_low']) {
                return [
                    'status' => 'Danger',
                    'color' => 'danger',
                    'icon' => self::getStatusIcon($parameter, 'danger'),
                    'message' => self::getStatusMessage($parameter, $value, 'danger')
                ];
            } else {
                return [
                    'status' => 'Warning',
                    'color' => 'warning',
                    'icon' => self::getStatusIcon($parameter, 'warning'),
                    'message' => self::getStatusMessage($parameter, $value, 'warning')
                ];
            }
        }

        if ($value > $paramThresholds['normal_high']) {
            if ($value >= $paramThresholds['danger_low']) {
                return [
                    'status' => 'Danger',
                    'color' => 'danger',
                    'icon' => self::getStatusIcon($parameter, 'danger'),
                    'message' => self::getStatusMessage($parameter, $value, 'danger')
                ];
            } else if ($value >= $paramThresholds['warning_low']) {
                return [
                    'status' => 'Warning',
                    'color' => 'warning',
                    'icon' => self::getStatusIcon($parameter, 'warning'),
                    'message' => self::getStatusMessage($parameter, $value, 'warning')
                ];
            }
        }

        // If value is within normal range

        return [
            'status' => 'Normal',
            'color' => 'success',
            'icon' => self::getStatusIcon($parameter, 'normal'),
            'message' => self::getStatusMessage($parameter, $value, 'normal')
        ];
    }

    /**
     * Get parameter thresholds for vaname shrimp ponds
     *
     * @return array
     */
    public static function getThresholds(): array
    {
        return [
            'ph' => [
                'normal_low' => 6.8,
                'normal_high' => 8.5,
                'warning_low' => 6.5,
                'warning_high' => 6.7,
                'danger_low' => 9.0,
                'danger_high' => 9.0
            ],
            'suhu' => [
                'normal_low' => 24.0,
                'normal_high' => 29.0,
                'warning_low' => 22.0,
                'warning_high' => 23.9,
                'danger_low' => 32.0,
                'danger_high' => 32.0
            ],
            'kekeruhan' => [
                'normal_low' => 5.0,
                'normal_high' => 30.0,
                'warning_low' => 0.0,
                'warning_high' => 4.9,
                'danger_low' => 60.0,
                'danger_high' => 60.0
            ]
        ];
    }

    /**
     * Get status icon based on parameter and status
     *
     * @param string $parameter
     * @param string $status
     * @return string
     */
    private static function getStatusIcon(string $parameter, string $status): string
    {
        $icons = [
            'ph' => [
                'normal' => 'fas fa-flask',
                'warning' => 'fas fa-flask',
                'danger' => 'fas fa-flask'
            ],
            'suhu' => [
                'normal' => 'fas fa-thermometer-half',
                'warning' => 'fas fa-thermometer-half',
                'danger' => 'fas fa-thermometer-full'
            ],
            'kekeruhan' => [
                'normal' => 'fas fa-eye',
                'warning' => 'fas fa-eye',
                'danger' => 'fas fa-eye-slash'
            ]
        ];

        return $icons[$parameter][$status] ?? 'fas fa-question-circle';
    }

    /**
     * Get status message based on parameter, value and status
     *
     * @param string $parameter
     * @param float $value
     * @param string $status
     * @return string
     */
    private static function getStatusMessage(string $parameter, float $value, string $status): string
    {
        $messages = [
            'ph' => [
                'normal' => "pH level optimal untuk udang vaname",
                'warning' => "pH level mendekati batas kritis",
                'danger' => "pH level kritis! Segera lakukan penyesuaian"
            ],
            'suhu' => [
                'normal' => "Suhu air optimal untuk udang vaname",
                'warning' => "Suhu air mendekati batas kritis",
                'danger' => "Suhu air kritis! Segera lakukan penyesuaian"
            ],
            'kekeruhan' => [
                'normal' => "Tingkat kekeruhan dalam batas normal",
                'warning' => "Tingkat kekeruhan meningkat, perlu perhatian",
                'danger' => "Tingkat kekeruhan kritis! Segera lakukan perawatan"
            ]
        ];

        $unit = self::getParameterUnit($parameter);

        return sprintf("%s (%.1f%s)", $messages[$parameter][$status], $value, $unit);
    }

    /**
     * Get parameter unit
     *
     * @param string $parameter
     * @return string
     */
    private static function getParameterUnit(string $parameter): string
    {
        $units = [
            'ph' => '',
            'suhu' => '°C',
            'kekeruhan' => ' NTU'
        ];

        return $units[$parameter] ?? '';
    }

    /**
     * Get all parameter statuses for a sensor reading
     *
     * @param float $ph
     * @param float $suhu
     * @param float $kekeruhan
     * @return array
     */
    public static function getAllParameterStatuses(float $ph, float $suhu, float $kekeruhan): array
    {
        return [
            'ph' => self::determineParameterStatus('ph', $ph),
            'suhu' => self::determineParameterStatus('suhu', $suhu),
            'kekeruhan' => self::determineParameterStatus('kekeruhan', $kekeruhan)
        ];
    }

    /**
     * Get overall water quality status based on individual parameter statuses
     *
     * @param array $parameterStatuses
     * @return array
     */
    public static function getOverallStatus(array $parameterStatuses): array
    {
        $statusCounts = [
            'Danger' => 0,
            'Warning' => 0,
            'Normal' => 0
        ];

        foreach ($parameterStatuses as $status) {
            $statusCounts[$status['status']]++;
        }

        if ($statusCounts['Danger'] > 0) {
            return [
                'status' => 'Danger',
                'color' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'message' => 'Kualitas air kritis! Perlu tindakan segera'
            ];
        }

        if ($statusCounts['Warning'] > 0) {
            return [
                'status' => 'Warning',
                'color' => 'warning',
                'icon' => 'fas fa-exclamation-circle',
                'message' => 'Kualitas air perlu perhatian'
            ];
        }

        return [
            'status' => 'Normal',
            'color' => 'success',
            'icon' => 'fas fa-check-circle',
            'message' => 'Kualitas air dalam kondisi baik'
        ];
    }
}
