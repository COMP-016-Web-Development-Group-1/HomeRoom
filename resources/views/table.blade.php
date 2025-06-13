<x-app-layout title="Properties">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Properties
        </h2>
    </x-slot>

    <div class="max-w-(--breakpoint-2xl) mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xs sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Properties (Index)
            </div>

            <!-- Start Conversation Modal/Form -->
            <!-- You can include this in any view where you want users to start conversations -->

            <div class="mb-4">
                <button onclick="openStartConversationModal()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Start New Conversation
                </button>
            </div>

            <!-- Modal -->
            <div id="start-conversation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium">Start New Conversation</h3>
                                <button onclick="closeStartConversationModal()"
                                    class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <form action="{{ route('messages.start') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Select User
                                    </label>
                                    <select name="recipient_id" id="recipient_id"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                        <option value="">Choose a user...</option>
                                        @foreach (\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                        Message
                                    </label>
                                    <textarea name="message" id="message" rows="3"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Type your message..." required></textarea>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeStartConversationModal()"
                                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                        Start Conversation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function openStartConversationModal() {
                    document.getElementById('start-conversation-modal').classList.remove('hidden');
                }

                function closeStartConversationModal() {
                    document.getElementById('start-conversation-modal').classList.add('hidden');
                }
            </script>

            {{-- <x-input.textarea name="description" rows="5" placeholder="Enter your description..." :disabled="false">
                {{ old('description') }}
            </x-input.textarea> --}}

            <x-table.container id="hello-table">
                <x-slot name="header">
                    <x-table.header>Name</x-table.header>
                    <x-table.header>Release Date</x-table.header>
                    <x-table.header>NPM Downloads</x-table.header>
                    <x-table.header>Growth</x-table.header>
                </x-slot>

                <x-slot name="body">
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Flowbite</td>
                        <td>2021-09-25</td>
                        <td>269000</td>
                        <td>49%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">React</td>
                        <td>2013-05-24</td>
                        <td>4500000</td>
                        <td>24%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Angular</td>
                        <td>2010-09-20</td>
                        <td>2800000</td>
                        <td>17%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Vue</td>
                        <td>2014-02-12</td>
                        <td>3600000</td>
                        <td>30%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Svelte</td>
                        <td>2016-11-26</td>
                        <td>1200000</td>
                        <td>57%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Ember</td>
                        <td>2011-12-08</td>
                        <td>500000</td>
                        <td>44%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Backbone</td>
                        <td>2010-10-13</td>
                        <td>300000</td>
                        <td>9%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">jQuery</td>
                        <td>2006-01-28</td>
                        <td>6000000</td>
                        <td>5%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Bootsx-table.rowap</td>
                        <td>2011-08-19</td>
                        <td>1800000</td>
                        <td>12%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Foundation</td>
                        <td>2011-09-23</td>
                        <td>700000</td>
                        <td>8%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Bulma</td>
                        <td>2016-10-24</td>
                        <td>500000</td>
                        <td>7%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Next.js</td>
                        <td>2016-10-25</td>
                        <td>2300000</td>
                        <td>45%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Nuxt.js</td>
                        <td>2016-10-16</td>
                        <td>900000</td>
                        <td>50%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Meteor</td>
                        <td>2012-01-17</td>
                        <td>1000000</td>
                        <td>10%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Aurelia</td>
                        <td>2015-07-08</td>
                        <td>200000</td>
                        <td>20%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Inferno</td>
                        <td>2016-09-27</td>
                        <td>100000</td>
                        <td>35%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Preact</td>
                        <td>2015-08-16</td>
                        <td>600000</td>
                        <td>28%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Lit</td>
                        <td>2018-05-28</td>
                        <td>400000</td>
                        <td>60%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Alpine.js</td>
                        <td>2019-11-02</td>
                        <td>300000</td>
                        <td>70%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Stimulus</td>
                        <td>2018-03-06</td>
                        <td>150000</td>
                        <td>25%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Solid</td>
                        <td>2021-07-05</td>
                        <td>250000</td>
                        <td>80%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Qwik</td>
                        <td>2022-05-03</td>
                        <td>180000</td>
                        <td>95%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Asx-table.rowo</td>
                        <td>2021-06-08</td>
                        <td>320000</td>
                        <td>85%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Remix</td>
                        <td>2021-11-23</td>
                        <td>420000</td>
                        <td>65%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">SvelteKit</td>
                        <td>2021-03-22</td>
                        <td>280000</td>
                        <td>72%</td>
                    </x-table.row>
                </x-slot>
            </x-table.container>

            <x-table.container id="programming-language-table">
                <x-slot name="header">
                    <x-table.header>Name</x-table.header>
                    <x-table.header>Release Date</x-table.header>
                    <x-table.header>NPM Downloads</x-table.header>
                    <x-table.header>Growth</x-table.header>
                </x-slot>

                <x-slot name="body">
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Flowbite</td>
                        <td>2021-09-25</td>
                        <td>269000</td>
                        <td>49%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">React</td>
                        <td>2013-05-24</td>
                        <td>4500000</td>
                        <td>24%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Angular</td>
                        <td>2010-09-20</td>
                        <td>2800000</td>
                        <td>17%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Vue</td>
                        <td>2014-02-12</td>
                        <td>3600000</td>
                        <td>30%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Svelte</td>
                        <td>2016-11-26</td>
                        <td>1200000</td>
                        <td>57%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Ember</td>
                        <td>2011-12-08</td>
                        <td>500000</td>
                        <td>44%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Backbone</td>
                        <td>2010-10-13</td>
                        <td>300000</td>
                        <td>9%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">jQuery</td>
                        <td>2006-01-28</td>
                        <td>6000000</td>
                        <td>5%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Bootsx-table.rowap</td>
                        <td>2011-08-19</td>
                        <td>1800000</td>
                        <td>12%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Foundation</td>
                        <td>2011-09-23</td>
                        <td>700000</td>
                        <td>8%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Bulma</td>
                        <td>2016-10-24</td>
                        <td>500000</td>
                        <td>7%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Next.js</td>
                        <td>2016-10-25</td>
                        <td>2300000</td>
                        <td>45%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Nuxt.js</td>
                        <td>2016-10-16</td>
                        <td>900000</td>
                        <td>50%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Meteor</td>
                        <td>2012-01-17</td>
                        <td>1000000</td>
                        <td>10%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Aurelia</td>
                        <td>2015-07-08</td>
                        <td>200000</td>
                        <td>20%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Inferno</td>
                        <td>2016-09-27</td>
                        <td>100000</td>
                        <td>35%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Preact</td>
                        <td>2015-08-16</td>
                        <td>600000</td>
                        <td>28%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Lit</td>
                        <td>2018-05-28</td>
                        <td>400000</td>
                        <td>60%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Alpine.js</td>
                        <td>2019-11-02</td>
                        <td>300000</td>
                        <td>70%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Stimulus</td>
                        <td>2018-03-06</td>
                        <td>150000</td>
                        <td>25%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Solid</td>
                        <td>2021-07-05</td>
                        <td>250000</td>
                        <td>80%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Qwik</td>
                        <td>2022-05-03</td>
                        <td>180000</td>
                        <td>95%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Asx-table.rowo</td>
                        <td>2021-06-08</td>
                        <td>320000</td>
                        <td>85%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Remix</td>
                        <td>2021-11-23</td>
                        <td>420000</td>
                        <td>65%</td>
                    </x-table.row>
                    <x-table.row>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">SvelteKit</td>
                        <td>2021-03-22</td>
                        <td>280000</td>
                        <td>72%</td>
                    </x-table.row>
                </x-slot>
            </x-table.container>
        </div>
    </div>
</x-app-layout>
