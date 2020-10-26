@extends('layout.index')

@section('title')
    База знаний | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid py-5">
        <h1 class="text-center text-primary mt-3">База знаний</h1>
        <div class="kb row">
            <div class="col-md-5 position-relative">
                <nav id="navbar-scrollspy" class="navbar navbar-dark position-md-fixed">
                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link" href="#ets2-paint">Связь</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ml-3" href="#discord">Как скачать Дискорд?</a>
                            <a class="nav-link ml-3" href="#ts3">Как скачать Team Speak 3?</a>
                            <a class="nav-link ml-3" href="#ts3-overlay">Оверлей для TS3</a>
                        </nav>
                        <a class="nav-link" href="#ets2-paint">ETS2 и ATS</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ml-3" href="#console">Настройка консоли</a>
                            <a class="nav-link ml-3" href="#cargo">Как искать груз на конвой?</a>
                        </nav>
                        <a class="nav-link" href="#ets2-paint">TruckersMP</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ml-3" href="#truckersmp">Как скачать клиент TruckersMP?</a>
                            <a class="nav-link ml-3" href="https://bit.ly/3gDlfTU" target="_blank">Правила TruckersMP на русском языке</a>
                            <a class="nav-link ml-3" href="#promods">Как скачать карту ProMods?</a>
                        </nav>
                        @auth
                            <a class="nav-link" href="#ets2-paint">ВТК</a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ml-3" href="#ets2-paint">Оффициальный окрас в ETS2</a>
                                <a class="nav-link ml-3" href="#ats-paint">Оффициальный окрас в ATS</a>
                                <a class="nav-link ml-3" href="#plate">Номерной знак ВТК</a>
                                <a class="nav-link ml-3" href="#plates">Таблица номеров</a>
                                <a class="nav-link ml-3" href="#money">Эвики</a>
                                <a class="nav-link ml-3" href="#donate">Помощь ВТК</a>
                                <a class="nav-link ml-3" href="#links">Полезные ссылки</a>
                            </nav>
                        @endauth
                    </nav>
                </nav>
            </div>
            <div data-spy="scroll" data-target="#navbar-scrollspy" data-offset="0" class="scrollspy col-md-7">
                <h2 class="text-primary">Связь</h2>
                <h4 id="discord">Как скачать Дискорд?</h4>
                <p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium atque cum dolorem labore, minus modi nihil obcaecati omnis, pariatur porro quibusdam ratione sint. Assumenda consequuntur eum facilis quis veritatis voluptate.</span><span>Adipisci aliquam et in ipsa magni molestias placeat saepe veritatis? Blanditiis cumque doloremque doloribus enim ipsam iure labore, maxime mollitia, nemo perferendis reprehenderit sequi soluta voluptas. Magni maiores molestias quam.</span><span>Inventore maiores nam qui! Exercitationem incidunt pariatur quia. Amet facilis illo laboriosam nobis quo quos repudiandae velit! Adipisci at ducimus et hic iste maiores, odit quae quibusdam temporibus velit. Eligendi?</span><span>Atque cupiditate hic ipsa maxime quidem saepe tempora. A amet, cupiditate debitis dicta dolor ea eligendi enim explicabo, id iste magni nisi odio officiis quaerat quidem ratione sit suscipit veniam.</span><span>Alias amet animi, aperiam beatae consequatur corporis cum deleniti deserunt dolor error eveniet excepturi fugiat id ipsam itaque maiores nemo nihil obcaecati perspiciatis quaerat quidem quod repellendus saepe sequi, sunt!</span><span>Accusantium aperiam architecto beatae cumque eveniet fugit minus placeat porro, reiciendis voluptate? Aut in laborum optio rem. Accusantium, consequuntur, debitis esse ex expedita iusto minima mollitia nulla quaerat rerum sapiente!</span><span>Accusantium asperiores beatae commodi consequatur cum dignissimos dolores et excepturi harum in, magnam modi nemo placeat praesentium provident quae quod ratione rerum sint soluta sunt suscipit vero voluptas! Nisi, nostrum.</span><span>Aspernatur dicta et, expedita ipsum iste maiores maxime optio, praesentium quod reiciendis, tempora tempore totam voluptatibus. Eveniet facere itaque maiores maxime minima nemo non obcaecati perferendis placeat quae, ratione rerum.</span><span>Accusantium alias architecto at, blanditiis eum fugiat neque non rerum suscipit veritatis. Culpa quidem repellendus soluta! Accusantium cumque doloribus nam. Aperiam consectetur dolor doloremque illum, itaque nostrum officia quam tempore.</span><span>Cum cupiditate esse et hic id illum natus, pariatur recusandae temporibus voluptas? Asperiores blanditiis dolore doloribus magnam minima natus, officiis totam! Commodi enim est eum impedit labore modi reprehenderit sed.</span></p>
                <h4 id="ts3">Как скачать Team Speak 3?</h4>
                <p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dolore error exercitationem expedita facere fugiat id, nemo neque nisi nobis perspiciatis, praesentium quaerat quia quis quisquam quo temporibus unde!</span><span>A accusamus at atque dolores eaque facilis, illo, porro rem repellendus sequi sunt totam veritatis? Aperiam consequuntur corporis eius, illo incidunt magnam modi non quasi reprehenderit similique temporibus veniam, vero?</span><span>Beatae deserunt eveniet iste natus nihil porro saepe! Alias cumque delectus exercitationem laborum nobis officiis quia quidem, tempore voluptate! Dolor laborum officia voluptates? Culpa cumque enim excepturi magni non veritatis.</span><span>A animi autem officiis optio tenetur! Accusamus aliquam animi asperiores aspernatur deserunt eligendi ex explicabo, fugit minus modi molestias necessitatibus nulla perferendis, provident quas ullam unde veniam voluptas? Dolorem, eum.</span><span>Beatae distinctio eius ex harum maiores mollitia, necessitatibus tempora velit! Accusantium amet animi consectetur corporis eius eum expedita harum illo, molestias necessitatibus nesciunt nostrum odit repudiandae soluta unde vitae voluptatum.</span><span>Dicta doloribus et eveniet id minus! Culpa harum iusto molestias soluta! Ad aliquid assumenda debitis, distinctio, eaque et ex fugiat, hic in minima natus neque ratione voluptatum? Autem, pariatur veniam!</span><span>Cumque dolorum ducimus in nihil praesentium? Amet animi aspernatur commodi culpa deleniti dicta distinctio eos ex, hic nemo nobis nulla odio omnis rerum sed sequi similique unde. Amet, eius, ipsa?</span><span>Ad amet architecto aspernatur commodi corporis culpa cum delectus earum eius eligendi incidunt ipsum nihil placeat quaerat quo quod recusandae repellat, repudiandae rerum saepe sequi soluta tenetur voluptatibus. Facilis, nulla?</span><span>Animi architecto consequuntur cum cupiditate deleniti, distinctio dolor dolorum ducimus error esse hic in ipsam laboriosam maiores nam nihil non odio officia perferendis quasi, quis rem rerum tenetur velit voluptas.</span><span>Accusamus aliquam at aut autem debitis dolor fuga, hic inventore itaque libero odit pariatur quod sapiente, sed tempora. Adipisci dolore eum incidunt repellat! Dolor, non, praesentium? Distinctio doloribus maxime provident.</span>.</p>
                <h4 id="ts3-overlay">Оверлей для TS3</h4>
                <p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda consequatur dolores iste laborum modi similique, sunt tempora. Accusantium blanditiis dolorum esse expedita placeat similique, suscipit tempora? Ducimus facere libero praesentium.</span><span>Ad, aliquid aspernatur assumenda at autem commodi consequatur consequuntur dignissimos dolores earum eligendi facere ipsam laborum magni minus molestiae nulla optio praesentium quas quisquam, quos repellat sunt voluptatem voluptates voluptatibus.</span><span>Accusamus, in magni molestias nostrum odio officia voluptatibus! A doloremque, facilis in ipsum laborum neque placeat quas quidem reprehenderit vitae! Hic ipsa itaque magnam porro repellendus sint tempore unde voluptate.</span><span>Aliquid animi architecto aut beatae consectetur cumque dolor dolore eius enim eos error inventore iste nesciunt nisi omnis perferendis perspiciatis possimus praesentium quia reprehenderit sint soluta, sunt suscipit tempore vel!</span><span>Labore laborum laudantium magnam maxime nemo, nihil nisi non obcaecati officiis quia. Beatae consequatur cupiditate doloremque ipsum magnam molestiae nisi non officia, omnis perspiciatis quis sequi sit suscipit tempora ullam.</span><span>Amet consequatur corporis facilis fugiat maxime, natus nihil quasi quibusdam quod totam. Aperiam architecto dolore doloribus excepturi harum labore nemo? Corporis, dignissimos eos eveniet excepturi maiores repellendus rerum sit voluptates.</span><span>Amet dignissimos dolorem est eveniet modi nemo quas unde ut! Delectus dolorum id laudantium quo voluptas. A aliquam autem cumque iste iure iusto, nesciunt quidem saepe, ullam ut voluptate voluptatem.</span><span>Ab accusamus asperiores commodi consequuntur debitis distinctio dolorum ducimus eaque error esse fugiat harum impedit labore maxime molestias nemo officiis praesentium quisquam recusandae repellendus similique sit, temporibus tenetur unde voluptatem?</span><span>Accusamus alias ea exercitationem facilis ipsa iste nesciunt quam quas similique vitae! Aliquid dolores eos eveniet impedit itaque nostrum pariatur perspiciatis, saepe sunt temporibus voluptatibus voluptatum? Asperiores doloremque saepe sed.</span><span>Animi beatae commodi consequatur cum cumque dolore error esse ex fugiat inventore ipsum iste laborum libero odio perspiciatis praesentium quae quod ratione, rem repellat rerum, tempore velit voluptatibus! Dicta, iste?</span></p>
            </div>
        </div>
    </div>
    <script>$('body').scrollspy({ target: '#navbar-scrollspy' })</script>

@endsection
