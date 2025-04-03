<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="text-center visible-print">
        {!! QrCode::size(200)->margin(2)->generate(
                route('user.absen-datang', [
                    'userId' => $getRecord()->id,
                    'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(10)->format('H:i'),
                ]),
            ) !!}
    </div>
</x-dynamic-component>
