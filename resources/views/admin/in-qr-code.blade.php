<div class="visible-print py-5 text-center">
    {!! QrCode::size(100)->margin(2)->generate(
            route('user.absen-datang', [
                'userId' => $getRecord()->id,
                'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(1)->format('H:i'),
            ]),
        ) !!}
</div>
