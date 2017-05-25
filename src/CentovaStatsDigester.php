<?php

namespace Comeul\CentovaDigester;

use Illuminate\Support\Collection;

class CentovaStatsDigester
{
    private $filepath;
    private $rawData;
    private $totalListenerByDay;
    private $averageListenersByHours;
    private $totalListenerByMonth;
    private $averageListenersByMonth;

    /**
     * Create a new Skeleton Instance.
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->rawData = collect([]);
        $this->totalListenerByDay = 0;
        $this->averageListenersByHours = 0;
        $this->totalListenerByMonth = 0;
        $this->averageListenersByMonth = 0;

        $this->loadFile();
        $this->processListenersByDay();
        $this->processListenersByMonth();
        $this->processAverageListenersByHours();
        $this->processAverageListenersByMonth();
    }

    private function processAverageListenersByHours()
    {
        $this->averageListenersByHours = $this->rawData->groupBy('hour');
        $this->averageListenersByHours = $this->averageListenersByHours->map(function ($item, $key) {
            $average = round($item->average('total'));
            return $average;
        })->toArray();

        ksort($this->averageListenersByHours);

        return $this;
    }

    private function processAverageListenersByMonth()
    {
        $this->averageListenersByMonth = $this->rawData->groupBy(function ($item) {
            return Carbon::parse($item['day'])->format('m-Y');
        });
        $this->averageListenersByMonth = $this->averageListenersByMonth->map(function ($item, $key) {
            $average = round($item->average('total'));
            return $average;
        })->toArray();

        return $this;
    }

    private function processListenersByMonth()
    {
        $this->totalListenerByMonth = $this->rawData->groupBy(function ($item) {
            return Carbon::parse($item['day'])->format('m-Y');
        });
        $this->totalListenerByMonth = $this->totalListenerByMonth->map(function ($item, $key) {
            $total = round($item->sum('total'));
            return $total;
        })->toArray();

        return $this;
    }

    private function processListenersByDay()
    {
        $this->totalListenerByDay = $this->rawData->groupBy('day');
        $this->totalListenerByDay = $this->totalListenerByDay->map(function ($item, $key) {
            $total = round($item->sum('total'));
            return $total;
        })->toArray();

        return $this;
    }

    private function loadFile()
    {
        Excel::load($this->filepath)->each(function (Collection $line) {
            $day = Carbon::parse($line->date)->toDateString();
            $hour = Carbon::parse($line->date)->toTimeString();

            $this->rawData->push([
                'day' => $day,
                'hour' => $hour,
                'total' => $line->total,
            ]);
        });

        return $this;
    }

    public function exportFile($name)
    {
        Excel::create($name, function ($excel) {

            $excel->sheet('Total par jour', function ($sheet) {

                $sheet->fromArray(array($this->totalListenerByDay), 0, 'A1', false, true);
            });

            $excel->sheet('Total par mois', function ($sheet) {

                $sheet->fromArray(array($this->totalListenerByMonth), 0, 'A1', false, true);
            });

            $excel->sheet('Moyenne par heure', function ($sheet) {

                $sheet->fromArray(array($this->averageListenersByHours), 0, 'A1', false, true);
            });

            $excel->sheet('Moyenne par mois', function ($sheet) {

                $sheet->fromArray(array($this->averageListenersByMonth), 0, 'A1', false, true);
            });
        })->export('xls');
    }
}
