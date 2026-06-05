<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight">
            {{ auth()->user()->isLearner() ? '🎓 My Training History' : '📋 Training Records' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg border border-slate-200 overflow-hidden">

                {{-- Learner View --}}
                @if(auth()->user()->isLearner())
                    <div class="p-8">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[12px] font-black uppercase tracking-widest text-slate-500">
                                    <th class="p-4">Course Title</th>
                                    <th class="p-4 text-center">Completion Date</th>
                                    <th class="p-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($records as $record)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-4 font-bold text-slate-900">{{ $record->course->title }}</td>
                                        <td class="p-4 text-center text-sm text-slate-500">{{ $record->completed_at }}</td>
                                        <td class="p-4 text-right">
                                             @if($record->progress_percentage == 100)
                                                <a href="{{ route('certificates.download', $record->course_id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-black uppercase rounded shadow hover:bg-indigo-700 transition">
                                                Download Certificate
                                            </a>
                                             @else
                                                <span class="text-[10px] text-slate-300 italic"></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="p-12 text-center text-slate-400 italic">No completed training found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                {{-- Teacher / Admin view --}}
                @else
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[12px] font-black uppercase tracking-widest text-slate-500">
                                    <th class="p-6">Full Name</th>
                                    <th class="p-6">Course Title</th>
                                    <th class="p-6">Progress</th>
                                    <th class="p-6 text-center">Status</th>
                                    <th class="p-6 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($records as $record)
                                    @foreach($record->enrolments as $enrolment)
                                        @php
                                            $progress = $record->courseProgress->where('course_id', $enrolment->course_id)->first();
                                            $percent = $progress->progress_percentage ?? 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="p-6 font-bold text-slate-900">{{ $record->name }}</td>
                                            <td class="p-6 text-sm font-bold text-indigo-600">{{ $enrolment->course->title }}</td>
                                            <td class="p-6 w-1/6">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-1 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                                        <div class="bg-indigo-600 h-full" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-black text-slate-700">{{ $percent }}%</span>
                                                </div>
                                            </td>
                                            <td class="p-6 text-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $percent == 100 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $percent == 100 ? 'Completed' : 'In Progess' }}
                                                </span>
                                            </td>
                                            <td class="p-6 text-right">
                                                @if($percent == 100)
                                                    <a href="{{ route('certificates.download', $enrolment->course_id) }}?user_id={{ $record->id }}" class="text-xs font-black text-indigo-600 hover:underline">Download Certificate</a>
                                                @else
                                                    <span class="text-[10px] text-slate-300 italic">--</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
