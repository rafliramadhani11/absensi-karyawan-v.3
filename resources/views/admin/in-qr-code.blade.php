<div class="py-5 text-center visible-print">
    {!! QrCode::size(100)->margin(2)->generate(
            route('user.absen-datang', [
                'userId' => $getRecord()->id,
                'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(1)->format('H:i'),
            ]),
        ) !!}
</div>
