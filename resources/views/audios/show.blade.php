<x-app-layout>
    <audio id='audio' controls="" src="" alt="">audio</audio>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    オーディオの一覧画面です！
                    <ul>
                        @foreach ($audios as $audio)
                            {{-- <audio controls="" src="{{ Storage::url('audios/' . $audio->file) }}" id="voiceContent"
                            alt=""></audio> --}}
                            <li>
                                <span>・{{ $audio->article_id }}
                                    {{ $loop->iteration }}<a class="audioSource"
                                        href="{{ Storage::url('audios/' . $audio->file) }}">{{ Storage::url('audios/' . $audio->file) }}
                                    </a></span>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    'use strict';

    var playlist = [];

    var audio = document.getElementById('audio');
    const audioSources = document.querySelectorAll(".audioSource");

    audioSources.forEach((element) => {
        playlist.push(element.textContent);
    });

    // document.body.appendChild(audio);
    // audio.style.width = '100%';
    // audio.style.height = 'auto';
    // audio.controls = true;
    // audio.volume = 0.2;

    audio.setAttribute('src', playlist[0]);
    // audio.src = playlist[0];
    audio.play();

    var index = 0;
    audio.addEventListener('ended', function() {
        index++;
        if (index < playlist.length) {
            // audio.src = playlist[index];
            audio.setAttribute('src', playlist[index]);
            audio.play();
            // } else {
            //     audio.src = playlist[0];
            //     audio.play();
            //     index = 0;
        }
    });
</script>

{{-- <script type="text/javascript">
    'use strict';

    var playlist = [
        'http://localhost/storage/audios/20231111154533_1699665237.webm',
        'http://localhost/storage/audios/20231111162028_blob',
        //   './sample4.wav',
        //   './sample5.wav'
    ]

    var audio = document.createElement('audio');
    document.body.appendChild(audio);
    audio.style.width = '100%';
    audio.style.height = 'auto';
    audio.controls = true;
    audio.volume = 0.2;

    audio.src = playlist[0];
    audio.play();

    var index = 0;
    audio.addEventListener('ended', function() {
        index++;
        if (index < playlist.length) {
            audio.src = playlist[index];
            audio.play();
        // } else {
        //     audio.src = playlist[0];
        //     audio.play();
        //     index = 0;
        }
    });
</script> --}}
