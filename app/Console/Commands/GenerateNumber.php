<?php

namespace App\Console\Commands;

use App\Models\SequenceNumber;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'number:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the number';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');
        $sequenceNumber = SequenceNumber::where('date', $today)->first();
        if (!$sequenceNumber) {
            SequenceNumber::create([
                'n' => 1,
                'date' => $today
            ]);
        } else {
            $sequenceNumber->increment('n');
        }

        $number = '1/' . Carbon::now()->format('dmy') . $sequenceNumber->n;

        $this->info($number);
    }
}
