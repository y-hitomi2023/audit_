<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- タイトル --}}
                    {{-- {{ $articles[1]->body }}; --}}
                    <button id="book_title">{{ $book->title }}</button>
                </div>
            </div>
            <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
                <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">

                    {{-- テーブル --}}
                    <table border="1">
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                        </tr>
                        @foreach ($articles as $article)
                            @if ($loop->iteration % 4 == 0)
                                <td><a
                                        href="{{ route('articles.show', $article) }}">{{ Str::limit($article->body, 50) }}</a>
                                </td>
                                </tr>
                            @elseif ($loop->iteration % 4 == 1)
                                <tr>

                                    <td><a
                                            href="{{ route('articles.show', $article) }}">{{ Str::limit($article->body, 50) }}</a>
                                    </td>

                                    {{-- <img src="{{ Storage::url('images/posts/' . '20231109212458_パソコン部屋（夜・画面のみON）.jpg') }}"
                                    alt="" class="circle">
                            </td> --}}
                                @else
                                    <td><a
                                            href="{{ route('articles.show', $article) }}">{{ Str::limit($article->body, 50) }}</a>
                                    </td>
                            @endif
                        @endforeach
                    </table>

                </div>
            </div>


            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data"
                class="rounded pt-3 pb-8 mb-4">
                @csrf

                {{-- <input type="file" id="file" name="image" class="form-control" /> --}}
                <input type="file" name="image" class="border-gray-300">

                <button type="submit">アップロード</button>
            </form>

            <div class="class1">
                <img src="{{ Storage::url('images/materials/' . '20231111135455_iconmonstr-video-15-240.png') }}"
                    alt="" class="circle" id="playButton" onclick="buttonTest()">
            </div>
</x-app-layout>

<script>
    function buttonClick(element) {

        var popupWrapper = document.getElementById('popup-wrapper');
        var close = document.getElementById('close');
        var tableContent = document.getElementById("tableContent");
        var popupContent = document.getElementById("popupContent");
        var tableContent = document.getElementById("tableContent");
        var voiceContent = document.getElementById("voiceContent");
        // async getVoice();
        // route('memos.index') }
        // route('messages.show', "こんばんは");
        // const response = async await fetch('https://jsonplaceholder.typicode.com/todos/');

        // var text = tableContent.textContent;
        var speaker = Math.random() * 61;
        var text = element.textContent;
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
                autoplay
                voiceContent.setAttribute('controls', autoplay)
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

    // async function getVoice() {
    //     var xhr = new XMLHttpRequest();
    //     xhr.open('GET',
    //         "https://api.tts.quest/v3/voicevox/synthesis?text=%E7%A2%BA%E8%AA%8D%E3%83%86%E3%82%B9%E3%83%88&speaker=3"
    //     );
    //     xhr.send();

    //     xhr.onreadystatechange = function() {
    //         if (xhr.readyState === 4 && xhr.status === 200) {

    //             //データを取得後の処理を書く
    //             alert("get");
    //         }
    //     }
    // }
</script>

<script>
    function buttonTest() {
        alert("test");
    }
</script>

{{-- 以下録音部分 --}}
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
<script>
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

                        const audioBlob = new Blob(this.audioData);
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

                        // axios.post('/articles', audioBlob).then((response) => {
                        //     user.value = response.data;
                        //     if (response.data.file_path) showUserImage.value = true;
                        // });

                        // //アップロードするファイルのデータ取得
                        // // var fileData = document.getElementById("upload_file").files[0];
                        // //フォームデータを作成する
                        // var form = new FormData();
                        // //フォームデータにアップロードファイルの情報追加
                        // form.append("file", audioBlob);

                        // $.ajax({
                        //     type: "POST",
                        //     url: "{{ route('articles.store') }}",
                        //     data: form,
                        //     processData: false,
                        //     contentType: false,
                        //     success: function(response) {
                        //         if (response.is_success) {
                        //             buttonTest();
                        //             //モーダルの処理
                        //             //エラーメッセージ非表示
                        //             $('#searchErrorMessage').html('');
                        //             $('#searchErrorMessage').hide();
                        //             //モーダルを閉じる
                        //             $('#file_upload_modal').modal('toggle');
                        //         } else {
                        //             buttonTest();
                        //             //モーダルでのエラーメッセージ表示処理
                        //             var msg = "";
                        //             $.each(response.errors_message, function(i, v) {
                        //                 msg += v + "<br>";
                        //             });
                        //             $('#searchErrorMessage').html(msg);
                        //             $('#searchErrorMessage').show();
                        //         }
                        //     },
                        //     error: function(response) {
                        //         //エラー時の処理
                        //         buttonTest();
                        //     }
                        // });


                    });
                    this.status = 'ready';


                });

        }
    });

    function postTest() {

        alert("test");
        const formData = new FormData();

        formData.append("username", "Groucho");
        formData.append("accountnum", 123456); // 数値 123456 は直ちに文字列 "123456" へ変換されます

        // HTML の file input でユーザーが選択したファイル
        formData.append("userfile", fileInputElement.files[0]);

        // ファイルのような JavaScript オブジェクト
        const content = '<q id="a"><span id="b">hey!</span></q>'; // 新しいファイルの本体…
        const blob = new Blob([content], {
            type: "text/xml"
        });

        formData.append("webmasterfile", blob);

        const request = new XMLHttpRequest();
        // request.open("POST", "http://foo.com/submitform.php");
        request.open("POST", "http://localhost/audios");
        request.send(formData);

    }
</script>
