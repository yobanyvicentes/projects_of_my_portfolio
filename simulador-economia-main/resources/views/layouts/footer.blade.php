<div class="max-w-7xl mx-auto p-6 lg:p-8">
    <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
        <div class="text-center text-sm sm:text-left">&nbsp;</div>

        <div class="flex flex-col items-center text-sm text-gray-500 dark:text-gray-400 sm:flex-row sm:justify-end sm:ml-0">
            <div class="flex items-center space-x-1">
                <select class="bg-transparent border-none p-0 text-sm text-gray-500 dark:text-gray-400 focus:ring-0 cursor-pointer hover:text-gray-700 dark:hover:text-white">
                    <option value="designed">Designed by</option>
                    <option value="developed">Developed by</option>
                    <option value="built">Built by</option>
                    <option value="created">Created by</option>
                    <option value="powered">Powered by</option>
                    <option value="made">Made with ❤️ by</option>
                </select>

                <span>Yobany Vicentes - All rights reserved &copy; {{ date('Y') }}</span>
            </div>

            <a href="{{ url('/') }}" class="ml-1 underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Economy Simulator
            </a>
        </div>
    </div>
</div>
