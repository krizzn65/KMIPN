<?php

namespace App\Events;

use App\Helpers\QualityHelper;
use App\Models\Sensor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The formatted sensor data.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct(Sensor $sensor)
    {
        // Format the data exactly like the original API endpoint
        $parameterStatuses = QualityHelper::getAllParameterStatuses($sensor->ph, $sensor->suhu, $sensor->kekeruhan, $sensor->kualitas);
        $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

        $this->data = [
            'id' => $sensor->id,
            'ph' => $sensor->ph,
            'suhu' => $sensor->suhu,
            'kekeruhan' => $sensor->kekeruhan,
            'kualitas' => $sensor->kualitas,
            'quality' => $sensor->kualitas, // Alias for consistency
            'created_at' => $sensor->created_at,
            'updated_at' => $sensor->updated_at,
            'parameter_statuses' => $parameterStatuses,
            'overall_status' => $overallStatus,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('sensor-data'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'SensorDataUpdated';
    }
}
