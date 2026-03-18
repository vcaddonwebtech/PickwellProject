<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToDoExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    { 
        $query = Todo::with(['todoUser', 'todoAssignUsers.assignUserDetail', 
                    'todoTasks' => function($query) {
                        $query->orderBy('id', 'desc');
                    },
                    'todoTasks.prioritys'])
                    ->latest();

        $todoDetail = $query->get()->map(function ($todo) {
            $firstTask = $todo->todoTasks->first();

            return [
                'Reminder Date' => $firstTask ? date('d-m-Y', strtotime($firstTask->date)) : '',
                'Title' => $todo->title ?? '',
                'Description' => $todo->description ?? '',
                'User' => $todo->todoUser->name ?? '',
                'Assign Users' => $todo->todoAssignUsers->map(function ($todoAssignUser) {
                    return $todoAssignUser->assignUserDetail ? $todoAssignUser->assignUserDetail->name : null;
                })->filter()->implode(', ') ?? '',
                'Assign Date & Time' => $todo->assign_date_time ? date('d-m-Y H:i:s', strtotime($todo->assign_date_time)) : '',
                'Status' => $todo->status != 1 ? 'In Progress' : 'Done',
                'Comment' => $firstTask ? $firstTask->comment_first : '',
                'Priority' => $firstTask && $firstTask->prioritys ? $firstTask->prioritys->priority : '',
            ];
        });

        return $todoDetail;
    }

    public function headings(): array
    {
        return [
            'Reminder Date',
            'Title',
            'Description',
            'User',
            'Assign Users',
            'Assign Date & Time',
            'Status',
            'Comment',
            'Priority'
        ];
    }
}
