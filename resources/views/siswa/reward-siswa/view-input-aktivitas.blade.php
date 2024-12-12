<tr>
    <td>{{$no}}</td>
    <td>{{ $value->nm_aktivitas_reward_siswa }}</td>

    <td>
        <input id="checkbox-{{$value->id_aktivitas_reward_siswa}}" type="checkbox" name="jawaban[{{$value->id_aktivitas_reward_siswa}}]" class="filled-in" value="1">
        <label for="checkbox-{{$value->id_aktivitas_reward_siswa}}">Ya</label>
    </td>
</tr>