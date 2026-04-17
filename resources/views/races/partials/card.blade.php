@php($showTop3 ??= false)

<div class="race-card f1-card group bg-gray-900 rounded-xl overflow-hidden cursor-pointer border border-white/10 reveal"
     data-name="{{ $race['name'] }}"
     data-date="{{ $race['date'] }}"
     data-location="{{ $race['location'] }}"
     data-status="{{ $race['status'] }}"
     data-img="{{ $race['img'] }}"
     data-length="{{ $race['length'] }}"
     data-turns="{{ $race['turns'] }}"
     data-lap-record="{{ $race['lapRecord'] }}"
     data-description="{{ $race['description'] }}"
     data-results-url="{{ $race['resultsUrl'] }}">

    <div class="overflow-hidden relative">
        <img src="{{ $race['img'] }}" alt="{{ $race['name'] }}"
             class="w-full h-48 object-cover transform transition-transform duration-500 group-hover:scale-110">
        <span class="absolute top-3 right-3 px-2 py-1 text-xs font-bold rounded text-white {{ $race['statusClass'] }}">
            {{ $race['status'] }}
        </span>
    </div>

    <div class="p-4">
        <p class="text-xs text-gray-400 uppercase tracking-widest">{{ $race['date'] }}</p>
        <h4 class="text-xl font-bold mt-1 audiowide-regular">{{ $race['name'] }}</h4>
        <p class="text-sm text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 mt-1">
            {{ $race['tagline'] ?? 'Press to see more info' }}
        </p>
        <p class="text-gray-400 mt-1">{{ $race['location'] }}</p>
    </div>

    @if($showTop3)
        @if(!empty($race['top3']))
            <div class="border-t border-white/10 bg-black/40 px-4 py-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs tracking-widest text-gray-400 uppercase">Top 3 Finishers</span>
                    <a href="{{ $race['resultsUrl'] }}"
                       class="text-xs text-red-400 hover:text-red-300"
                       onclick="event.stopPropagation();">Full results →</a>
                </div>
                <ul class="space-y-2">
                    @foreach($race['top3'] as $i => $res)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full shrink-0 text-xs font-bold
                                    @if($i===0) bg-gradient-to-br from-yellow-300 to-yellow-600 text-black
                                    @elseif($i===1) bg-gradient-to-br from-gray-200 to-gray-500 text-black
                                    @else bg-gradient-to-br from-orange-400 to-orange-700 text-black @endif">
                                    {{ $i + 1 }}
                                </span>
                                <span class="text-sm font-semibold text-white truncate">{{ $res['driver'] }}</span>
                                <span class="text-xs text-gray-400 truncate">({{ $res['team'] }})</span>
                            </div>
                            <span class="text-sm tabular-nums text-gray-300 shrink-0 ml-2">{{ $res['time'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="border-t border-white/10 bg-black/40 px-4 py-3 text-center">
                <span class="text-sm text-gray-500">No results yet</span>
            </div>
        @endif
    @endif
</div>
