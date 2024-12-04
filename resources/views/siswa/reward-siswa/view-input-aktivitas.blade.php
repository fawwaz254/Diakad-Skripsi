<div class="form-group">
    <label>{{$no}}. {{ $value->nm_aktivitas_reward_siswa }}</label><br>

    <input id="checkbox-{{$value->id_aktivitas_reward_siswa}}" type="checkbox" name="jawaban[{{$value->id_aktivitas_reward_siswa}}]" class="filled-in" value="1">
    <label for="checkbox-{{$value->id_aktivitas_reward_siswa}}">Ya</label>
</div>
<br>