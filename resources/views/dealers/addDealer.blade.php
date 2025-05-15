@extends('home')

@section('content')

<h2 class="main-title">Add Dealer</h2>

<div class="form form3 w-full px-4 py-6 space-y-6 bg-white rounded-md dark:bg-darker">
    

    @if (Session::has('success'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center">
            <div class="success-model p-6 rounded-md shadow-lg w-1/3">
                <h2 class="text-lg font-bold text-center text-green-600">Success</h2>
                <p class="text-center">{{ Session::get('success') }}</p>
                <div class="text-center mt-4">
                    <button onclick="window.location.href='{{ url('overviewDealer') }}'" class="px-4 py-2 text-white rounded-md hover:bg-blue-700">Ok</button>
                </div>
            </div>
        </div>
    @endif

    @if (Session::has('fail'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('fail') }}
        </div>
    @endif
    
    <form action="{{ route('addDealers')}}" method="POST" class="space-y-6">

        <div id="loadingModal" class="flex fixed inset-0 items-center justify-center hidden">
            <div class="success-model p-6 rounded-md shadow-lg w-1/3">
                <h2 class="text-lg font-bold text-center text-blue-600">Processing...</h2>
                <p class="text-center">Please wait while we add the OMC.</p>
                <div class="flex justify-center mt-4">
                    <div class="loader border-t-4 border-blue-500 rounded-full w-10 h-10 animate-spin"></div>
                </div>
            </div>
        </div>

        @csrf
        
        <div class="flex px-4 inp">
            
            <div class="w-full px-4 put">
                <label class="h3 px-4">First Name</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="first_name" placeholder="First Name"/>
                @error('first_name')
                <p class="text-danger">{{ $message }}</p>
                @enderror 
            </div>  

            <div class="w-full px-4 put">
                <label class="h3 px-4">Middle Name</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="middle_name" placeholder="Middle Name"/>
                @error('middle_name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">Last Name</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="last_name" placeholder="Last Name"/>
                @error('last_name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex px-4 inp">

            <div class="w-full px-4 put">
                <label class="h3 px-4">Phone No</label>
                <input id="phone" 
                       class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" 
                       type="tel" 
                       name="phone" 
                       placeholder="+232 88 000000"
                       pattern="\+232\s\d{2}\s\d{6}"
                       required>
                @error('phone')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">E-Mail</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="email" placeholder="E-Mail"/>
                @error('email')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">License No</label>
                <input class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" type="text" name="licence_no" placeholder="License No"/>
                @error('licence_no')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex px-4 inp">

            <div x-data="{ open: false, selected: [], omcs: {{ Js::from($omcs) }}, search: '' }" class="samee relative w-full px-4 put">
                <label class="lab h3 px-4">OMCs</label>
                
                <div @click="open = !open" class="sameAs w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 cursor-pointer">
                    <template x-if="selected.length">
                        <span x-text="selected.map(id => omcs.find(o => o.id === id)?.omcName).join(', ')"></span>
                    </template>
                    <template x-if="!selected.length">
                        <span class="text-gray-400">Select OMC(s)</span>
                    </template>
                </div>
                
                <div x-show="open" class="sameAs2 dropd absolute z-10 mt-2 w-full max-h-20 overflow-y-auto border border-gray-300 rounded-md shadow-lg dark:bg-darker dark:border-gray-700" @click.away="open = false">
                    <div class="px-4 py-2">
                        <input type="text" x-model="search" placeholder="Search..." class="w-full px-3 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker text-white dark:text-gray-200" />
                    </div>
                    
                    <div class="px-4 py-2">
                        <template x-for="omc in omcs" :key="omc.id">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       :value="omc.id"
                                       :checked="selected.includes(omc.id)"
                                       @change="selected.includes(omc.id)
                                                ? selected = selected.filter(i => i !== omc.id)
                                                : selected.push(omc.id)"
                                       class="mr-2">
                                <span x-text="omc.omcName"></span>
                            </div>
                        </template>
                    </div>
                </div>
                
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="omc_id[]" :value="id">
                </template>
            
                @error('omc_id')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-4 put">
                <label class="h3 px-4">Licence Expiry Date</label>
                <input class="ios-pikaday w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker" 
                       type="text" 
                       name="licence_exp"
                       id="licence_exp"
                       placeholder="Select date" />
                @error('licence_exp')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            
        </div>
      
        <div class="btn px-4">
            <button type="submit" class="w-full max-w-sm px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker">
            Add Dealer
           </button>
        </div>

    </form>

    <script>
        document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("loadingModal").classList.remove("hidden");
        });

        document.getElementById('phone').addEventListener('input', function (e) {
            let phone = e.target.value.replace(/\D/g, '');
    
            if (phone.startsWith('232')) {
                phone = phone.substring(3)
            }
    
            let formatted = '+232 ';
            if (phone.length > 0) {
                formatted += phone.substring(0, 2);
            }
            if (phone.length > 2) {
                formatted += ' ' + phone.substring(2, 8);
            }
    
            e.target.value = formatted;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('phone');
            const phoneRegex = /^\+232\s\d{2}\s\d{6}$/;
    
            if (!phoneRegex.test(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number in the format: +232 00 000000');
                phoneInput.focus();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
        new Pikaday({
            field: document.getElementById('licence_exp'),
            format: 'YYYY-MM-DD',
            minDate: 1980,
            yearRange: [1990, new Date().getFullYear() + 10],
            showDaysInNextAndPreviousMonths: true,
            enableSelectionDaysInNextAndPreviousMonths: true,
            i18n: {
                previousMonth: 'Previous',
                nextMonth: 'Next',
                months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                weekdays: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                weekdaysShort: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']
            }
        });
    });
    </script>
    
    

</div>

@endsection