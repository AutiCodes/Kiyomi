@extends('home::layouts.master')

<!-- AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

@section('title', 'Vlucht aanmelden')

<main>
  @section('content')
    <div class="container mt-4 mb-4">
      <!-- Error or success reporting -->
      @if ($errors->any())
        <div class="alert alert-danger">
          <h1>Oeps!</h1>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if (session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
      @endif
      @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
      @endif
      <!-- End error or success reporting -->
      <form class="col-lg-6 offset-lg-3 pt-4 pb-4" id="plane_submittion" action="{{ route('form.store') }}" method="POST">
        @csrf
        <div class="justify-content-center">
          <!-- TOP TEXT AND IMAGE -->
          <img src="/media/images/field.jpg" class="img-fluid rounded mt-3">
          <h2 class="text-white text-center pt-3 pb-3">Registratie aanvang modelvliegen TRMC</h2>

          <script>
            $(document).ready(function () {
              $('select').selectize({
                  sortField: 'text'
              });
            });
          </script>

          <!-- NAME -->
          <div class="form-group">
          <label for="name" class="text-white font-weight-bold">Naam modelvlieger:</label>
            <select id="name" name="name" placeholder="Voornaam Achternaam" required onChange="nameFunction(this)">
              <option value="">Selecteer een naam</option>
              @foreach ($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
              @endforeach
            </select>
           <small id="name" class="form-text text-muted">Staat je naam er niet tussen? Contacteer dan het bestuur om je naam toe te voegen.</small>
          </div>

          <!-- DATE -->
          <div class="form-group">
            <label for="date" class="text-white font-weight-bold">Selecteer een datum:</label>
            <input type="date" id="date" name="date" class="form-control" required onchange="requiredHideViewer(this)">
          </div>

          <!-- TIME -->
          <div class="form-group mt-2">
            <label for="time" class="text-white font-weight-bold">Selecteer een tijd:</label>
            <input type="time" id="time" name="time" class="form-control" required onchange="requiredHideViewer(this)">
          </div>

          <!-- WHAT MODELS -->
          <div class="form-group mt-2">
            <label for="time" class="text-white font-weight-bold">Met welke modellen wil je gaan vliegen?</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value=1 id="CheckboxPlane" name="model_type[]" onclick="checkBoxes(this)">
              <label class="form-check-label text-white font-weight-bold ml-1" for="CheckboxPlane">
                Gemotoriseerd modelvliegtuig
              </label>
            </div>
            <hr>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value=2 id="CheckboxGlider" name="model_type[]" onclick="checkBoxes(this)">
              <label class="form-check-label text-white font-weight-bold ml-2" for="CheckboxGlider">
                Modelzweefvliegtuig
              </label>
            </div>
            <hr>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value=3 id="CheckBoxHelicopter" name="model_type[]" onclick="checkBoxes(this)">
              <label class="form-check-label text-white font-weight-bold ml-2" for="CheckBoxHelicopter">
                Helicopter
              </label>
            </div>
            <hr>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value=4 id="CheckboxDrone" name="model_type[]" onclick="checkBoxes(this)">
              <label class="form-check-label text-white font-weight-bold ml-2" for="CheckboxDrone">
                Drone
              </label>
            </div>
            <hr>
          </div>

          <!-- PLANE -->
          <div id="CheckboxPlane_div" style="display: none;" class="bg-dark rounded p-2 mt-2 bg-opacity-75">
            <h3 class="text-white">Modelvliegtuig</h3>
            <!-- POWER TYPE -->
            <div class="form-group">
              <label for="power_type_select_plane" class="text-white font-weight-bold">Onder welke klasse ga je vliegen?</label>
              <select class="form-control" id="power_type_select_plane" name="power_type_select_plane">
                <option disabled selected>Selecteer</option>
                <option value="<300W">< 300W</option>
                <option value="300W-1200W">300W-1200W</option>
                <option value="1200W-3000W">1200W-3000W</option>
              </select>
            </div>
            <!-- LIPO COUNT -->
            <div class="form-group">
              <label for="lipo_count_select_plane" class="text-white font-weight-bold">Aantal te verwachten vluchten (aantal Lipo's)</label>
              <select class="form-control" id="lipo_count_select_plane" name="lipo_count_select_plane">
                <option value=1>1</option>
              </select>
            </div>
          </div>

          <!-- GLIDER -->
          <div id="CheckboxGlider_div" style="display: none;" class="bg-dark mt-3 rounded p-2 bg-opacity-75">
            <h3 class="text-white">Modelzweefvliegtuig</h3>
            <!-- POWER TYPE -->
            <div class="form-group">
              <label for="power_type_select_glider" class="text-white font-weight-bold">Onder welke klasse ga je vliegen?</label>
              <select class="form-control" id="power_type_select_glider" name="power_type_select_glider">
                <option disabled selected>Selecteer</option>
                <option value="<300W">< 300W</option>
                <option value="300W-1200W">300W-1200W</option>
                <option value="1200W-3000W">1200W-3000W</option>
              </select>
            </div>
            <!-- LIPO COUNT -->
            <div class="form-group">
              <label for="lipo_count_select_glider" class="text-white font-weight-bold">Aantal te verwachten vluchten (aantal Lipo's)</label>
              <select class="form-control" id="lipo_count_select_glider" name="lipo_count_select_glider">
                <option value=1>1</option>
              </select>
            </div>
          </div>

          <!-- HELICOPTER -->
          <div id="CheckBoxHelicopter_div" style="display: none;" class="bg-dark mt-3 rounded p-2 bg-opacity-75">
            <h3 class="text-white">Helicopter</h3>
            <!-- POWER TYPE -->
            <div class="form-group">
              <label for="power_type_select_helicopter" class="text-white font-weight-bold">Onder welke klasse ga je vliegen?</label>
              <select class="form-control" id="power_type_select_helicopter" name="power_type_select_helicopter">
                <option disabled selected>Selecteer</option>
                <option value="<300W">< 300W</option>
                <option value="300W-1200W">300W-1200W</option>
                <option value="1200W-3000W">1200W-3000W</option>
              </select>
            </div>
            <!-- LIPO COUNT -->
            <div class="form-group">
              <label for="lipo_count_select_helicopter" class="text-white font-weight-bold">Aantal te verwachten vluchten (aantal Lipo's)</label>
              <select class="form-control" id="lipo_count_select_helicopter" name="lipo_count_select_helicopter">
                <option value=1>1</option>
              </select>
            </div>
          </div>

          <!-- DRONE -->
          <div id="CheckboxDrone_div" style="display: none;" class="bg-dark mt-3 rounded p-2 bg-opacity-75">
            <h3 class="text-white">Drone</h3>
            <!-- POWER TYPE -->
            <div class="form-group">
              <label for="power_type_select_drone" class="text-white font-weight-bold">Onder welke klasse ga je vliegen?</label>
              <select class="form-control" id="power_type_select_drone" name="power_type_select_drone">
                <option disabled selected>Selecteer</option>
                <option value="<300W">< 300W</option>
                <option value="300W-1200W">300W-1200W</option>
                <option value="1200W-3000W">1200W-3000W</option>
              </select>
            </div>
            <!-- LIPO COUNT -->
            <div class="form-group">
              <label for="lipo_count_select" class="text-white font-weight-bold">Aantal te verwachten vluchten (aantal Lipo's)</label>
              <select class="form-control" id="lipo_count_select_drone" name="lipo_count_select_drone">
                <option value=1>1</option>
              </select>
            </div>
          </div>
        </div>

        <!-- reCAPTCHA -->
        <div class="form-group">
          <label for="text" class="Text-white font-weight-bold">Geheime vraag om te kijken of je geen robot bent</label>
          <input type="text" class="form-control" id="rechapcha_custom" name="rechapcha_custom" placeholder="Wat is 2 + 2?" required>
        </div>

        <!-- SEND FORM BUTTON -->
        <button type="submit" class="btn btn-success font-weight-bold mt-3" data-toggle="modal" data-target="#exampleModalCenter">Verzenden</button>
      </form>
      </div>

      <!-- HELP ICON -->
      <!--
      <a class="help_icon text-white mr-3 " data-toggle="modal" data-target="#helpModal" >
        <img class="img-fluid" src="/media/images/help.ico" alt="help" style="width: 50px;"></img>
      </a>
      -->
      <!-- HELP MODAL -->
      <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="helpModalLabel">Hulp</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <h4>Uitleg voor het invullen van het formulier:</h4>
              <p>Het formulier is gemaakt om je te helpen om een vlucht te plannen. Het formulier bevat een aantal vragen die je moet beantwoorden om een vlucht te plannen. De vragen zijn als volgt:</p>
              <p>1. Wat is uw naam? (Vul uw voor en achternaam in)</p>
              <p>2. Wat is uw vliegdatum? (De datum waarop u gaat vliegen)</p>
              <p>3. Wat is uw vliegtijd? (De tijd waarop u gaat vliegen)</p>
              <p>4. Met welke modellen gaat u vliegen? (meerdere zijn mogelijk. Vul voor ieder model de klassen in lipo aantal in.)</p>

              <p class="text-danger">Let er goed op dat alles goed is ingevuld! De rode letters geven aan wat nog ingevuld moet worden</p>

              <!-- -->
              <span aria-hidden="true"></span>
              <h4>Errors en contact:</h4>
              <p>Lukt het aanmelden nog niet of komt er een error? Neem dan contact met ons op via Email: <a href="mailto:webmaster@trmc.nl">webmaster@trmc.nl</a></p>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluit</button>
            </div>
          </div>
        </div>
      </div>
		</main>

    <style>
      body, html {
        background-color: #2f3031;
      }

      .help_icon {
          position: fixed;
          bottom:0;
          right: 0;
          padding: 10px;
      }

      input[type="checkbox"] {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 50%;
      }

      hr {
        padding-top: 1px;
        padding-bottom: 1px;
        background-color: #ffffff;
        margin-top: 5px;
        margin-bottom: 5px;
      }
    </style>

    <script>
      function checkBoxes(e) {
        if (e.checked) {
          document.getElementById(e.id + '_div').style.display = "block";
          document.getElementById('model_type_required').style.visibility = "hidden";
        } else {
          document.getElementById(e.id + '_div').style.display = "none";
          document.getElementById('model_type_required').style.visibility = "visible";
          }
      }

      function nameFunction(e) {
        // Check if name is already stored in local storage
        if(localStorage.getItem('name_id') == null) {
          // Store name in local storage
          localStorage.setItem('name_id', e.value);
        }
      }

      document.addEventListener('DOMContentLoaded', async () => {
        // Do nothing if browser doesn't support local storage
        if(typeof Storage === 'undefined') return;

        // Check if user has a name_id in local storage
        const user = localStorage.getItem('name_id');
        // If not, do nothing
        if(!user) return;

        document.getElementById('name').value = user;
      });
    </script>
@stop
