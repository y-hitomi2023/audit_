<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- タイトル --}}
                    {{-- {{ $articles[1]->body }}; --}}
                    <button id="test" onclick="buttonTest()">test</button>
                </div>
            </div>
            <div id="popup-wrapper">
                <div id="popup-inside">
                    <div id="close">x</div>
                    <div id="message">
                        {{-- <h2>あの人気のマンガが...</h2> --}}
                        <p id="popupContent"></p>
                        <a href="#">録音</a>
                        {{-- <audio controls
                    src="https://audio1.tts.quest//v1//data//2729b8e7308dd4453bfb9493686997626f3635051b9083103245c8127d3b460b//audio.mp3"></audio>
                     --}}
                        <audio controls autoplay src="" id="voiceContent" alt=""></audio>

                    </div>
                </div>
            </div>
            <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
                <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">
                    @foreach ($articles as $article)
                        <article class="w-full px-4 md:w-1/2 text-xl text-gray-800 leading-normal">
                            {{-- <a href="{{ route('articles.show', $article) }}"> --}}
                            {{-- <h2
                                    class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl break-words">
                                    {{ $article->title }}</h2>
                                <h3>{{ $article->user->name }}</h3> --}}
                            <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                                <span
                                    class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-1 day')) < $article->created_at ? 'NEW' : '' }}</span>
                                {{ $article->created_at }}
                            </p>
                            {{-- <img class="w-full mb-2" src="{{ $article->image_url }}" alt=""> --}}
                            <p class="text-gray-700 text-base" id ="tableContent" onclick="buttonClick(this)">
                                {{ Str::limit($article->body, 50) }}</p>
                            {{-- </a> --}}
                        </article>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data"
                class="rounded pt-3 pb-8 mb-4">
                @csrf

                {{-- <input type="file" id="file" name="image" class="form-control" /> --}}
                <input type="file" name="image" class="border-gray-300">

                <button type="submit">アップロード</button>
            </form>
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
