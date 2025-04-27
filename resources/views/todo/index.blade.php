<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for Ajax -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- important for Ajax CSRF -->
</head>

<body class="bg-gray-100 p-6">

    <div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
        <!-- Show All Tasks Checkbox -->
        <div class="flex items-center mb-4">
            <input type="checkbox" id="showAllTasksCheckbox" class="h-5 w-5 text-blue-600 mr-2">
            <label class="text-lg font-semibold">Show All Tasks</label>
        </div>

        <!-- Add Project -->
        <div class="flex items-center mb-6">
            <button class="bg-white text-black border border-gray-300 px-4 py-2 hover:bg-gray-100">
                0
            </button>
            <input id="taskName"
                type="text"
                placeholder="Project # To Do"
                class="flex-1 border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button id="addTaskButton" class="bg-green-500 text-white px-6 py-2 rounded-r hover:bg-green-600">
                Add
            </button>
        </div>

        <!-- Task Table -->
        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full table-fixed border border-gray-300">
                <tbody>
                    @foreach($tasks as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 text-center">
                            <input type="checkbox" class="h-5 w-5 text-blue-600 taskCheckbox" data-id="{{ $task->id }}" {{ $task->completed ? 'checked' : '' }}>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <div class="flex justify-between items-center">
                                <span>{{ $task->task_name }}</span>
                                <span class="text-sm text-gray-400">{{ $task->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <img src="https://i.pravatar.cc/40" alt="User" class="w-8 h-8 rounded-full">
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <button class="deleteTaskButton" data-id="{{ $task->id }}">
                                <svg class="w-6 h-6 text-gray-400 hover:text-red-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m4-4h2a2 2 0 012 2v2H9V5a2 2 0 012-2z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#addTaskButton').click(function() {
                var taskName = $('#taskName').val();

                if (taskName.trim() === '') {
                    alert('Task name cannot be empty.');
                    return;
                }

                $.ajax({
                    url: "{{ route('tasks.store') }}",
                    type: "POST",
                    data: {
                        task_name: taskName,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#taskTable tbody').prepend(`
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 text-center">
                        <input type="checkbox" class="h-5 w-5 text-blue-600 taskCheckbox" data-id="${response.task.id}" >
                    </td>
                    <td class="border border-gray-300 p-2">
                        <div class="flex justify-between items-center">
                            <span>${response.task.task_name}</span>
                            <span class="text-sm text-gray-400">just now</span>
                        </div>
                    </td>
                    <td class="border border-gray-300 p-2 text-center">
                        <img src="https://i.pravatar.cc/40" alt="User" class="w-8 h-8 rounded-full">
                    </td>
                    <td class="border border-gray-300 p-2 text-center">
                        <button class="deleteTaskButton" data-id="${response.task.id}">
                            <svg class="w-6 h-6 text-gray-400 hover:text-red-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m4-4h2a2 2 0 012 2v2H9V5a2 2 0 012-2z" />
                            </svg>
                        </button>
                    </td>
                </tr>
            `);

                            $('#taskName').val(''); // Clear input after adding
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status == 409) { // Duplicate task error
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });

            });
        });

        $(document).ready(function() {
            $('#showAllTasksCheckbox').change(function() {
                var showAll = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ route('tasks.fetch') }}",
                    type: "POST",
                    data: {
                        show_all: showAll,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#taskTable tbody').empty(); // Clear table first

                        response.tasks.forEach(function(task) {
                            var newRow = `
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 text-center">
                            <input type="checkbox" class="h-5 w-5 text-blue-600 taskCheckbox" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                        </td>
                        <td class="border border-gray-300 p-2">
                            <div class="flex justify-between items-center">
                                <span>${task.task_name}</span>
                                <span class="text-sm text-gray-400">just now</span>
                            </div>
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <img src="https://i.pravatar.cc/40" alt="User" class="w-8 h-8 rounded-full">
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <button class="deleteTaskButton" data-id="${task.id}">
                                <svg class="w-6 h-6 text-gray-400 hover:text-red-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m4-4h2a2 2 0 012 2v2H9V5a2 2 0 012-2z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    `;
                            $('#taskTable tbody').append(newRow);
                        });
                    },
                    error: function(xhr) {
                        alert('Failed to fetch tasks.');
                    }
                });
            });
        });

        $(document).on('click', '.deleteTaskButton', function() {
            var taskId = $(this).data('id');

            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }

            $.ajax({
                url: '/tasks/' + taskId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Remove the task row from table
                        $('#taskTable').find('button[data-id="' + taskId + '"]').closest('tr').remove();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Failed to delete the task.');
                }
            });
        });

        $(document).on('change', '.taskCheckbox', function() {
            var taskId = $(this).data('id');
            var isChecked = $(this).is(':checked');
            var showAllChecked = $('#showAllTasksCheckbox').is(':checked');

            $.ajax({
                url: '/tasks/' + taskId + '/toggle',
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (!showAllChecked && isChecked) {
                            // If "Show All Tasks" is not checked and task is completed, remove it
                            $('#taskTable').find('input[data-id="' + taskId + '"]').closest('tr').remove();
                        }
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Failed to update task.');
                }
            });
        });
    </script>

</body>

</html>