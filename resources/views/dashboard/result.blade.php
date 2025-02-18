<x-dashboard.header></x-dashboard.header>
@vite('resources/js/add-quiz.js')
<body class="bg-gray-50">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-dashboard.sidebar></x-dashboard.sidebar>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Navigation -->
        <x-dashboard.navbar></x-dashboard.navbar>

        <!-- Content -->
        <main class="p-6 lg:p-8">
            <div class="max-w-4xl mx-auto">
                <!-- Quiz Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $quiz->title }}</h1>
                    <p class="text-gray-600">{{ $quiz->description }}</p>
                </div>

                <!-- Questions Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Questions</h2>
                    <ol class="space-y-6">
                        @foreach($questions as $question)
                            <li class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <!-- Question -->
                                <div class="mb-3">
                                    <span class="text-gray-600 font-medium">Question {{ $loop->iteration }}:</span>
                                    <p class="text-gray-800 font-medium mt-1">{{ $question['question'] }}</p>
                                </div>

                                <!-- Answers -->
                                <div class="space-y-2 ml-4">
                                    <!-- Correct Answer -->
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-600 w-32">Correct Answer:</span>
                                        <span class="flex items-center text-green-600 font-medium">
                                            {{ $question['correct_answer'] }}
                                            <svg class="w-5 h-5 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                    </div>

                                    <!-- User's Answer -->
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-600 w-32">Your Answer:</span>
                                        <span class="flex items-center {{ $question['is_correct'] ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $question['user_answer'] }}
                                            @if($question['is_correct'])
                                                <svg class="w-5 h-5 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 ml-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </main>
    </div>
</div>
<x-dashboard.footer></x-dashboard.footer>
