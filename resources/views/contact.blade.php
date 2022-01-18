<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contacts Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-validation-errors></x-auth-validation-errors>
            @if(Session::has('errormsg'))
                {{ Session::get('errormsg') }}
            @endif
            <form class="excel-form" enctype="multipart/form-data" method="POST" action="{{ route('bulk-upload') }}">
            @csrf
            <x-label for="data_file">Excel Upload Contact</x-label>
            <x-input type="file" name="data_file" id="data_file" accepts="xlsx, csv"></x-input>
            </form> 
            <x-button type="button" onclick="trackBtn()" class="mb-4 mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Track Klaviyo</x-button>
            <div x-data="{ show: false }">
                <div class="flex">
                    <x-button @click="show=true" type="button" class="mb-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Contact</x-button>
                </div>
                <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 mt-6 top-0 bottom-0 right-0 w-full h-full fixed">
                    <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 600px;">
                        <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                            <button @click="show=false" class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                            <div class="px-6 mt-4 py-3 text-xl border-b font-bold">Create Contact</div>
                            <form method="POST" action="{{ route('create-contact') }}">
                            @csrf
                            <div class="p-6 flex-grow">
                                <div>
                                    <x-label for="first_name" :value="__('First Name')" />

                                    <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                                </div>
                                <div class="mt-4">
                                    <x-label for="email" :value="__('Email')" />

                                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                </div>
                                <div class="mt-4">
                                    <x-label for="email" :value="__('Phone')" />

                                    <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                                </div>
                            </div>
                            <div class="px-6 py-3 border-t">
                                <div class="flex justify-end">
                                    <x-button type="button" @click="show=false" type="button" class="bg-gray-700 text-gray-100 rounded px-4 py-2 mr-1">Close</x-button>
                                    <x-button type="submit">Save</x-button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50"></div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap">
                      <thead>
                        <tr class="text-left font-bold">
                          <th class="border px-6 py-4">First Name</th>
                          <th class="border px-6 py-4">Email</th>
                          <th class="border px-6 py-4">Phone</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                          <td class="border px-6 py-4">{{ $contact->first_name }}</td>
                          <td class="border px-6 py-4">{{ $contact->email }}</td>
                          <td class="border px-6 py-4">{{ $contact->phone }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script type="text/javascript">
    function trackBtn(){
        $.ajax({
            url: "{{ route('click-event') }}",
            method: "GET",
            data: "",
            success: function(response){
                 alert('Event recorded.');
            }

        });
    }

    $('#data_file').on('change', function(e){
        $('.excel-form').submit();
    });

</script>
