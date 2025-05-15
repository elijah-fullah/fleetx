@extends('home')

@section('content')

<h2 class="main-title">Update Station</h2>

<div class="form form3 w-full px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker">
    

    @if (Session::has('success'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center">
            <div class="success-model p-6 rounded-md shadow-lg w-1/3">
                <h2 class="text-lg font-bold text-center text-green-600">Success</h2>
                <p class="text-center">{{ Session::get('success') }}</p>
                <div class="text-center mt-4">
                    <button onclick="window.location.href='{{ url('overviewOMC') }}'" class="px-4 py-2 text-white rounded-md hover:bg-blue-700">Ok</button>
                </div>
            </div>
        </div>
    @endif

    @if (Session::has('fail'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('fail') }}
        </div>
    @endif
    
    <form action="{{ route('updateStation', $station->id) }}" method="POST" class="space-y-6">

        <div id="loadingModal" class="flex fixed inset-0 items-center justify-center hidden">
            <div class="success-model p-6 rounded-md shadow-lg w-1/3">
                <h2 class="text-lg font-bold text-center text-blue-600">Processing...</h2>
                <p class="text-center">Please wait while we Update the Station.</p>
                <div class="flex justify-center mt-4">
                    <div class="loader border-t-4 border-blue-500 rounded-full w-10 h-10 animate-spin"></div>
                </div>
            </div>
        </div>

        @csrf
        
        <div class="flex px-4 inp">
            
            <div class="w-full px-4 put">
                <label class="h3 px-4">Station Name</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="stationName" value="{{ old('stationName', $station->stationName) }}" placeholder="Station Name"/>
                @error('stationName')
                <p class="text-danger">{{ $message }}</p>
                @enderror 
            </div>
            
            <div class="w-full px-4 put">
                <label class="h3 px-4">District</label>
                <select id="district" name="district" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" name="district" placeholder="District">
                    <option value="{{ old('district', $station->district) }}">{{ old('district', $station->district) }}</option>
                </select>
                @error('district')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">Chiefdom</label>
                <select id="chiefdom" name="chiefdom" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" name="Chiefdom" placeholder="Chiefdom">
                    <option value="{{ old('chiefdom', $station->chiefdom) }}">{{ old('chiefdom', $station->chiefdom) }}</option>
                </select>
                @error('chiefdom')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex px-4 inp">

            <div x-data="{ open: false, selected: '{{ old('dealer_id', $station->dealer_id) }}'.split(',').map(id => id.trim()), dealers: {{ Js::from($dealers) }}, search: '' }" class="samee relative w-full px-4 put">
                <label class="lab h3 px-4">Dealers</label>
                
                <div @click="open = !open" class="sameAs w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 cursor-pointer">
                    <template x-if="selected.length">
                        <span x-text="selected.map(id => dealers.find(o => o.id.toString() === id.toString())?.first_name).join(', ')"></span>
                    </template>
                    <template x-if="!selected.length">
                        <span class="text-gray-400">{{ old('dealer_id', $station->dealer_id) }})</span>
                    </template>
                </div>
                
                <div x-show="open" class="sameAs2 dropd absolute z-10 mt-2 w-full max-h-20 overflow-y-auto border border-gray-300 rounded-md shadow-lg dark:bg-darker dark:border-gray-700" @click.away="open = false">
                    <div class="px-4 py-2">
                        <input type="text" x-model="search" placeholder="Search..." class="w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker text-white dark:text-gray-200" />
                    </div>
                    
                    <div class="px-4 py-2">
                        <template x-for="dealer in dealers" :key="dealer.id">
                            <div class="flex items-center">
                                <input type="checkbox" :value="dealer.id" :checked="selected.includes(dealer.id.toString())" @change="selected.includes(dealer.id.toString()) ? selected = selected.filter(i => i !== dealer.id.toString()) : selected.push(dealer.id.toString())" class="mr-2">
                                <span x-text="dealer.first_name"></span>
                            </div>
                        </template>
                    </div>
                </div>
                
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="omc_id[]" :value="id">
                </template>
            
                @error('dealer_id')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">Longitude</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="number" name="longitude" value="{{ old('longitude', $station->longitude) }}" placeholder="Longitude"/>
                @error('longitude')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>  
            
            <div class="w-full px-4 put">
                <label class="h3 px-4">Latitude</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="number" name="latitude" value="{{ old('latitude', $station->latitude) }}" placeholder="Latitude"/>
                @error('latitude')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex px-4 inp">

            <div class="px-4 put">
                <label class="h3 px-4">Address</label>
                <textarea name="address" cols="30" rows="5" class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="address" placeholder="{{ old('address', $station->address) }}">{{ old('address', $station->address) }}</textarea>
                @error('address')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>
      
        <div class="btn px-4">
            <button type="submit" class="w-full max-w-sm px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker">
            Update Station
           </button>
        </div>

    </form>

    <script>
        document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("loadingModal").classList.remove("hidden");
        });

        const districts = [
            'Kailahun',
            'Kenema',
            'Kono',
            'Bombali',
            'Koinadugu',
            'Tonkolili',
            'Kambia',
            'Port Loko',
            'Bo',
            'Bonthe',
            'Moyamba',
            'Pujehun'
        ];

        const districtChiefdoms = {
            'Kailahun': [
                'Dea – Baiwala',
                'Jaluahun – Segbwema',
                'Jawei – Daru',
                'Kissi Kama – Dea',
                'Kissi Teng – Kangama',
                'Kissi Tongi – Buedu',
                'Luawa – Kailahun',
                'Malema – Jojoima',
                'Mandu – Mobai',
                'Peje Bongre – Manowa',
                'Peje West – Bunumbu',
                'Penguia – Sandar',
                'Upper Bambara – Pendembu',
                'Yawei – Bandajuma'
            ],
    
            'Kenema': [
                'Dama – Giema',
                'Dodo – Dodo',
                'Gaura – Joru',
                'Gorama Mende – Tungie',
                'Kandu Leppiam – Gbado',
                'Koya – Baoma',
                'Langurama Ya – Baima',
                'Lower Bambara – Panguma',
                'Malehgohun – Sembehun',
                'Niawa – Sundumei',
                'Nomo – Faama',
                'Nongowa – Kenema',
                'Simbaru – Boajibu',
                'Small Bo – Blama',
                'Tunkia – Gorahun',
                'Wandor – Faala'
            ],
    
            'Kono': [
                'Fiama – Njagbwema',
                'Gbane Kandor – Koardu',
                'Gbane – Ngandorhun',
                'Gbense – Yardu',
                'Gorama Kono – Kangama',
                'Kamara – Tombodu',
                'Lei – Siama',
                'Mafindor – Kamiendor',
                'Nimikoro – Njaiama',
                'Nimiyama – Sewafe',
                'Sandor – Kayima',
                'Soa – Kainkordu',
                'Tankoro – New Sembehun',
                'Toli – Kondewakor'
            ],
    
            'Bombali': [
                'Biriwa – Kamabai',
                'Bombali Shebora – Makeni',
                'Gbanti Kamaranka – Kamaranka',
                'Gbendembu Ngowahun – Kalangba',
                'Libeisaygahun – Batkanu',
                'Magbaiamba Ndowahun – Hunduwa',
                'Makari Gbanti – Masongbon',
                'Paki Massabong – Mapaki',
                'Safroko Limba – Binkolo',
                'Sanda Loko – Kamalo',
                'Sanda Tenraren – Mateboi',
                'Sella Limba – Kamakwie',
                'Tambakha – Fintonia'
            ],
    
            'Koinadugu': [
                'Dembelia Sikunia – Sikunia',
                'Diang – Kondembaia',
                'Folasaba – Musaia',
                'Kasunko – Fadugu',
                'Mongo – Bendugu',
                'Neya – Krubola',
                'Nieni – Yiffin',
                'Sengbe – Yogomaia',
                'Sulima – Falaba',
                'Wara-Wara Bafodia – Bafodea',
                'Wara-Wara Yagala – Gbawuria'
            ],
    
            'Tonkolili': [
                'Gbonkolenken – Yele',
                'Kafe Simiria – Mabonto',
                'Kalanthuba – Kamankay',
                'Dansogoia - Bumbuna',
                'Kholifa Mabang - Mabang',
                'Kholifa Rowalla – Magburaka',
                'Kunike – Masingbi',
                'Kunike Barina – Makali',
                'Malal Mara – Rochin',
                'Sambaia – Bendugu',
                'Tane – Matotoka',
                'Yoni – Yonibana'
            ],
    
            'Kambia': [
                'Brimaia – Kukuna',
                'Gbinle Dixing – Tawuya',
                'Magbema – Kambia',
                'Mambolo – Mambolo',
                'Masungbala – Kawula',
                'Samu – Kychum',
                'Tonko Limba – Madina'
            ],
    
            'Port Loko': [
                'Bureh Kasseh – Mange',
                'Buya Romende – Foredugu',
                'Debia – Gbinti',
                'Kaffu Bullom – Mahera',
                'Koya – Songo',
                'Loko Masama – Petifu',
                'Maforki – Port Loko',
                'Marampa – Lunsar',
                'Masimera – Masimera',
                'Sanda Magbolontor – Sendugu',
                'T.M. Safroko – Miraykulay'
            ],
    
            'Bo': [
                'Badjia – Ungentle',
                'Bagbo – Jimmi',
                'Bagbwe – Ngarlu',
                'Baoma – Baoma',
                'Bumpe–Gao – Bumpe',
                'Gbo – Gbo',
                'Jaiama Bongor – Telu',
                'Kakua – Bo town',
                'Komboya – Njala',
                'Lugbu – Sumbuya',
                'Niawa Lenga – Nengbema',
                'Selenga – Dambala',
                'Tikonko – Tikonko',
                'Valunia – Mongere',
                'Wonde – Gboyama'
            ],
    
            'Bonthe': [
                'Bendu – Cha Bendu',
                'Bum – Madina',
                'Dema – Tissana',
                'Imperri – Gbangbama',
                'Jong – Mattru',
                'Kpanda – Kemo Motuo',
                'Kwamebai Krim – Benduma',
                'Nongoba Bullom – Gbap',
                'Sittia – Yonni',
                'Sogbini – Tihun',
                'Yawbeko – Talia'
            ],
    
            'Moyamba': [
                'Bagruwa – Sembehun',
                'Bumpe – Rotifunk',
                'Dasse – Mano',
                'Fakunya – Gandohun',
                'Kagboro – Shenge',
                'Kaiyamba – Moyamba',
                'Kamajei – Senehun',
                'Kongbora – Bauya',
                'Kori – Taiama',
                'Kowa – Njama',
                'Lower Banta – Gbangbatoke',
                'Ribbi – Bradford',
                'Timdale – Bomotoke',
                'Upper Banta – Mokelle'
            ],
    
            'Pujehun': [
                'Barri – Potoru',
                'Gallines Perri – Blama',
                'Kpaka – Masam',
                'Kpanga Kabonde – Pujehun town',
                'Makpele – Zimmi',
                'Malen – Sahn',
                'Mano Sa Krim – Gbonjema',
                'Kpanga Krim – Gobaru',
                'Peje – Futta',
                'Sorogbema – Fairo',
                'Sowa – Bandajuma',
                'Yakemo Kpukumu Krim – Karlu'
            ]
        };
  
        const districtSelect = document.getElementById('district');
        const chiefdomSelect = document.getElementById('chiefdom');

        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });

        districtSelect.addEventListener('change', function () {
            const selectedDistrict = this.value;
            const chiefdoms = districtChiefdoms[selectedDistrict] || [];
    
            chiefdomSelect.innerHTML = '<option value="">Select Chiefdom</option>';
    
            chiefdoms.forEach(chiefdom => {
                const option = document.createElement('option');
                option.value = chiefdom;
                option.textContent = chiefdom;
                chiefdomSelect.appendChild(option);
            });
        });
    </script>
    
    

</div>

@endsection