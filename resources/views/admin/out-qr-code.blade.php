<div class="visible-print text-center">
    {!! QrCode::size(100)->margin(2)->generate(
            route('user.absen-pulang', [
                'userId' => $getRecord()->id,
                'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(10)->format('H:i'),
            ]),
        ) !!}
</div>
