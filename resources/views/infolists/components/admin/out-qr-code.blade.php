<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="visible-print text-center">
        {!! QrCode::size(200)->margin(2)->generate(
                route('user.absen-pulang', [
                    'userId' => $getRecord()->id,
                    'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(5)->format('H:i'),
                ]),
            ) !!}
    </div>
</x-dynamic-component>
