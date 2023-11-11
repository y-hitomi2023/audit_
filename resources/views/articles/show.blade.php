<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">

        {{-- <x-flash-message :message="session('notice')" />

        <x-validation-errors :errors="$errors" /> --}}

        <article class="mb-2">
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl break-words">
                {{ $article->body }}</h2>
            {{-- <h3>{{ $article->script }}</h3> --}}
            <p class="text-sm mb-2 md:text-base font-normal text-gray-600" id="voice_script">
                {{ $article->script }}
            </p>
            {{-- <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                <span
                    class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-1 day')) < $article->created_at ? 'NEW' : '' }}</span>
                {{ $article->created_at }}
            </p> --}}

            <div id="open">
                {{-- <img src="{{ $article->image_url }}" alt="" class="mb-4"> --}}
                <img src="{{ Storage::url('images/materials/' . '20231111093022_iconmonstr-microphone-6-240.png') }}"
                    alt="" class="circle" id="mic" onclick="buttonTest()">

                <!-- モーダルウィンドウ内を開くボタンの指定 -->
                <button>録音</button>

            </div>

            <button onclick="postAudio()">test</button>
            {{-- <audio controls autoplay src="" id="voiceContent" alt=""></audio> --}}
            <audio onload="playSample()" controls autoplay src="" id="yourVoiceContent" alt=""></audio>
            <button id="upload_button" onclick="postAudio()">アップロード</button>


            <dialog class="dialog">
                <!-- モーダルウィンドウ内のテキストの指定 -->
                {{-- <button id="record">録音を開始する</button> --}}
                <div id="app">
                    <button class="btn btn-danger" type="button" v-if="status=='ready'"
                        @click="startRecording">録音を開始</button>
                    <button class="btn btn-primary" type="button" v-if="status=='recording'"
                        @click="stopRecording">録音を終了</button>
                </div>
                <!-- モーダルウィンドウ内を閉じるボタンの指定 -->
                <button id="close">キャンセル</button>
            </dialog>

            <button id="sample_button" onclick="playSample()">sample</button>


            {{-- <p class="text-gray-700 text-base break-words">{!! nl2br(e($article->body)) !!}</p> --}}
        </article>
        <div class="flex flex-row text-center my-4">
            @can('update', $article)
                <a href="{{ route('articles.edit', $article) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
            @endcan
            @can('delete', $article)
                <form action="{{ route('articles.destroy', $article) }}" method="article">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
                </form>
            @endcan
        </div>
        @auth
            <hr class="my-4">
            <div class="flex justify-end">
                <a href="{{ route('articles.comments.create', $article) }}"
                    class="bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline block">コメント登録</a>
            </div>
        @endauth


        {{-- コメント部分なので削除 --}}
        {{-- <section class="font-sans break-normal text-gray-900 ">
            @foreach ($comments as $comment)
                <div class="my-2">
                    <span class="font-bold mr-3">{{ $comment->user->name }}</span>
                    <span class="text-sm">{{ $comment->created_at }}</span>
                    <p class="break-all">{!! nl2br(e($comment->body)) !!}</p>
                    <div class="flex justify-end text-center">
                        @can('update', $comment)
                            <a href="{{ route('articles.comments.edit', [$article, $comment]) }}"
                                class="text-sm bg-green-400 hover:bg-green-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
                        @endcan
                        @can('delete', $comment)
                            <form action="{{ route('articles.comments.destroy', [$article, $comment]) }}" method="article">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                                    class="text-sm bg-red-400 hover:bg-red-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20">
                            </form>
                        @endcan
                    </div>
                </div>
                <hr>
            @endforeach
        </section> --}}

    </div>
</x-app-layout>

<script>
    // function buttonTest() {
    //     alert("test");
    // }

    let dialog = document.querySelector('dialog'); // dialog（モーダルダイアログ）の宣言
    let btn_open = document.getElementById('open'); // open（開く）ボタンの宣言
    let btn_close = document.getElementById('close'); // close（閉じる）ボタンの宣言
    let btn_record = document.getElementById('record'); // close（閉じる）ボタンの宣言
    btn_open.addEventListener('click', function() {
        // 開くボタンをクリックした場合の処理
        dialog.showModal();
    }, false);
    btn_close.addEventListener('click', function() {
        // 閉じるボタンをクリックした場合の処理
        dialog.close();
    }, false);
    // btn_record.addEventListener('click', function() {
    //     // レコードボタンをクリックした場合の処理
    //     startRecording;
    // }, false);
</script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
<script>
    var audioBlob = ""; //グローバル変数

    new Vue({
        el: '#app',
        data: {

            // ① 変数を宣言する部分
            status: 'init', // 状況
            recorder: null, // 音声にアクセスする "MediaRecorder" のインスタンス
            audioData: [], // 入力された音声データ
            audioExtension: '' // 音声ファイルの拡張子

        },
        methods: {

            // ② 録音を開始／ストップする部分
            startRecording() {

                this.status = 'recording';
                this.audioData = [];
                this.recorder.start();

            },
            stopRecording() {

                this.recorder.stop();
                this.status = 'ready';

            },

            // ④ 音声ファイルの拡張子を取得する部分
            getExtension(audioType) {

                let extension = 'wav';
                const matches = audioType.match(/audio\/([^;]+)/);

                if (matches) {

                    extension = matches[1];

                }

                return '.' + extension;

            }

        },
        mounted() {

            // ⑤ マイクにアクセス
            navigator.mediaDevices.getUserMedia({
                    audio: true
                })
                .then(stream => {

                    this.recorder = new MediaRecorder(stream);
                    this.recorder.addEventListener('dataavailable', e => {

                        this.audioData.push(e.data);
                        this.audioExtension = this.getExtension(e.data.type);

                    });
                    this.recorder.addEventListener('stop', () => {

                        // const audioBlob = new Blob(this.audioData);
                        audioBlob = new Blob(this.audioData);
                        const url = URL.createObjectURL(audioBlob);
                        let a = document.createElement('a');
                        a.href = url;
                        a.download = Math.floor(Date.now() / 1000) + this.audioExtension;
                        document.body.appendChild(a);
                        a.click();

                        const formData = new FormData();

                        // formData.append("username", "Groucho");
                        // formData.append("accountnum", 123456); // 数値 123456 は直ちに文字列 "123456" へ変換されます

                        // // HTML の file input でユーザーが選択したファイル
                        // formData.append("userfile", fileInputElement.files[0]);

                        // // ファイルのような JavaScript オブジェクト
                        // const content = '<q id="a"><span id="b">hey!</span></q>'; // 新しいファイルの本体…
                        // const blob = new Blob([content], {
                        //     type: "text/xml"
                        // });

                        // formData.append("webmasterfile", audioBlob);
                        formData.append("blob", audioBlob);

                        const request = new XMLHttpRequest();
                        // request.open("POST", "http://foo.com/submitform.php");
                        request.open("POST", "http://localhost/audios");
                        request.send(formData);

                        dialog.close();

                        let audio_yours = document.getElementById('yourVoiceContent');
                        let upload_button = document.getElementById('upload_button');

                        audio_yours.style.display = "block";
                        upload_button.style.display = "block";

                        // audio_yours.setAttribute('src', data.mp3StreamingUrl);
                        audio_yours.setAttribute('src', url);

                    });
                    this.status = 'ready';


                });

        }
    });

    function postAudio() {

        alert("test");

        const formData = new FormData();

        formData.append("blob", audioBlob);

        const request = new XMLHttpRequest();
        // request.open("POST", "http://foo.com/submitform.php");
        request.open("POST", "http://localhost/audios");
        request.send(formData);

        dialog.close();

        let audio_yours = document.getElementById('yourVoiceContent');
        let upload_button = document.getElementById('upload_button');

        // audio_yours.style.display = "block";
        upload_button.style.display = "none";

        // audio_yours.setAttribute('src', data.mp3StreamingUrl);
        // audio_yours.setAttribute('src', url);

        // audio_yours.getUserMedia('src', url);

        alert("end");

    }

    //以下AIサンプルを再生
    function playSample(element) {

        var voiceScript = document.getElementById('voice_script');
        var voiceContent = document.getElementById("yourVoiceContent");
        var text = voiceScript.textContent;
        alert(text);

        var speaker = Math.random() * 61;
        var uri = "https://api.tts.quest/v3/voicevox/synthesis?text=" + text + "&speaker=" + speaker;
        // var uri = "https://api.tts.quest/v3/voicevox/synthesis?text=" + text + "&speaker=60";
        var res1 = encodeURI(uri);

        const xhr = new XMLHttpRequest();
        // xhr.open("GET",
        //     "https://api.tts.quest/v3/voicevox/synthesis?text=%E7%A2%BA%E8%AA%8D%E3%83%86%E3%82%B9%E3%83%88&speaker=3"
        //     );
        xhr.open("GET",
            res1
        );
        xhr.send();
        xhr.responseType = "json";
        xhr.onload = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const data = xhr.response;
                console.log(data);
                // alert(data.mp3DownloadUrl);
                // voiceContent.setAttribute('src', data.mp3DownloadUrl);
                voiceContent.setAttribute('src', data.mp3StreamingUrl);
            } else {
                console.log(`Error: ${xhr.status}`);
            }
        };

        // alert(tableContent.textContent);
        popupContent.textContent = text;

        popupWrapper.style.display = "block";

        popupWrapper.addEventListener('click', e => {
            if (e.target.id === popupWrapper.id || e.target.id === close.id) {
                popupWrapper.style.display = 'none';
            }
        });
        // alert('Click');

        // let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
        //     width=0,height=0,left=-1000,top=-1000`;
        // open('https://www.sejuku.net/blog/', null, params);

        // let newWin = window.open("about:blank", "hello", "width=200,height=200");

        // newWin.document.write("Hello, world!");

    }
    var yes = document.getElementById('yes');
    var no = document.getElementById('no');

    //「はい」がクリックされたら
    yes.addEventListener('click', function() {
        console.log('yes')
    });

    //「いいえ」がクリックされたら
    no.addEventListener('click', function() {
        console.log('no')
    });
</script>
