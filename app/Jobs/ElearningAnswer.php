<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\JawabanTest;

class ElearningAnswer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $test_answer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $test_answer)
    {
        $this->test_answer = $test_answer;
    }

    /** 
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($jawaban_test = JawabanTest::where('id_pengguna', $this->test_answer['id_pengguna'])->where('id_test', $this->test_answer['id_test'])->where('id_soal', $this->test_answer['id_soal'])->first()) {
            if ($this->test_answer['id_tipe_soal'] == 1) {
                $jawaban_test->id_pilihan_soal =  $this->test_answer['id_pilihan_soal'];
                $jawaban_test->save();
            } elseif ($this->test_answer['id_tipe_soal'] == 2) {
                $jawaban_test->id_pilihan_soal =  $this->test_answer['jawaban_essay'];
                $jawaban_test->save();
            } else {
                $jawaban_test->id_pilihan_soal =  $this->test_answer['link_file'];
                $jawaban_test->id_pilihan_soal =  $this->test_answer['type_file'];
                $jawaban_test->save();
            }
        } else {
            JawabanTest::insert($this->test_answer);
        }
    }
}
