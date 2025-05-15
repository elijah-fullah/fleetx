    @extends('home')

    @section('content')
    
        <h2 class="main-title">Dashboard</h2>

        <div class="row stat-cards">

            <div class="col-md-6 col-xl-3">

                <article class="stat-cards-item">

                    <div class="stat-cards-icon primary">
                        <i data-feather="bar-chart-2" aria-hidden="true"></i>
                    </div>

                    <div class="stat-cards-info">

                        <p class="stat-cards-info__num">1478 286</p>
                        <p class="stat-cards-info__title">Total visits</p>
                        <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit success">
                                <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                            </span>Last month
                        </p>
                    
                    </div>
        
                </article>

            </div>
      
            <div class="col-md-6 col-xl-3">

                <article class="stat-cards-item">

                    <div class="stat-cards-icon warning">
                        <i data-feather="file" aria-hidden="true"></i>
                    </div>
          
                    <div class="stat-cards-info">

                        <p class="stat-cards-info__num">1478 286</p>
                        <p class="stat-cards-info__title">Total visits</p>
                        <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit success">
                                <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                            </span>
                            Last month
                        </p>

                    </div>
        
                </article>
      
            </div>
      
            <div class="col-md-6 col-xl-3">

                <article class="stat-cards-item">

                    <div class="stat-cards-icon purple">

                        <i data-feather="file" aria-hidden="true"></i>
                    </div>

                    <div class="stat-cards-info">

                        <p class="stat-cards-info__num">1478 286</p>
                        <p class="stat-cards-info__title">Total visits</p>
                        <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit danger">
                                <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                            </span>
                            Last month
                        </p>
          
                    </div>

                </article>

            </div>

            <div class="col-md-6 col-xl-3">

                <article class="stat-cards-item">

                    <div class="stat-cards-icon success">

                        <i data-feather="feather" aria-hidden="true"></i>

                    </div>

                    <div class="stat-cards-info">
                         <p class="stat-cards-info__num">1478 286</p>
                         <p class="stat-cards-info__title">Total visits</p>
                         <p class="stat-cards-info__progress">
                            <span class="stat-cards-info__profit warning">
                                <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                            </span>
                            Last month
                        </p>

                    </div>

                </article>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-9">

                <div class="chart">

                    <canvas id="myChart" aria-label="Site statistics" role="img"></canvas>

                </div>

                <div class="users-table table-wrapper">

                    <table class="posts-table">

                        <thead>

                            <tr class="users-table-info">

                                <th>Name</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-04.webp" type="image/webp"><img src="./img/avatar/avatar-face-04.png" alt="User Name"></picture>Jenny Wilson

                                    </div>

                                </td>
                                <td><span class="badge-pending">Pending</span></td>
                                <td>17.04.2021</td>

                                <td>

                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-03.webp" type="image/webp"><img src="./img/avatar/avatar-face-03.png" alt="User Name"></picture>Annette Black

                                    </div>

                                </td>

                                <td><span class="badge-pending">Pending</span></td>
                                <td>23.04.2021</td>
                                <td>
                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>
              
                            <tr>

                                <td>
                                    <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-02.webp" type="image/webp"><img src="./img/avatar/avatar-face-02.png" alt="User Name"></picture>Kathryn Murphy
                                    
                                    </div>

                                </td>
                                <td><span class="badge-active">Active</span></td>
                                <td>17.04.2021</td>
                                <td>

                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <td>

                                     <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-05.webp" type="image/webp"><img src="./img/avatar/avatar-face-05.png" alt="User Name"></picture>Guy Hawkins
                  
                                    </div>

                                </td>
                                <td><span class="badge-active">Active</span></td>
                                <td>17.04.2021</td>
                                <td>

                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>
                            <tr>

                                <td>

                                    <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-03.webp" type="image/webp"><img src="./img/avatar/avatar-face-03.png" alt="User Name"></picture>Robert Fox

                                    </div>

                                </td>
                                <td><span class="badge-active">Active</span></td>
                                <td>17.04.2021</td>
                                <td>

                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>
                            <tr>

                                <td>

                                    <div class="pages-table-img">

                                        <picture><source srcset="./img/avatar/avatar-face-03.webp" type="image/webp"><img src="./img/avatar/avatar-face-03.png" alt="User Name"></picture>Robert Fox
                  
                                    </div>

                                </td>
                                <td><span class="badge-active">Active</span></td>
                                <td>17.04.2021</td>
                                <td>

                                    <span class="p-relative">

                                        <button class="dropdown-btn transparent-btn" type="button" title="More info">

                                            <div class="sr-only">More info</div>
                                            <i data-feather="more-horizontal" aria-hidden="true"></i>

                                        </button>

                                        <ul class="users-item-dropdown dropdown">

                                            <li><a href="##">Edit</a></li>
                                            <li><a href="##">Quick edit</a></li>
                                            <li><a href="##">Trash</a></li>

                                        </ul>

                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="col-lg-3">

                <article class="customers-wrapper">

                    <canvas id="customersChart" aria-label="Customers statistics" role="img"></canvas>

                </article>

                <article class="white-block">

                    <div class="top-cat-title">

                        <h3>Top categories</h3>
                        <p>28 Categories, 1400 Posts</p>

                    </div>
                    
                    <ul class="top-cat-list">

                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">Lifestyle <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Dailiy lifestyle articles <span class="purple">+472</span></div>

                            </a>

                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">Tutorials <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Coding tutorials <span class="blue">+472</span></div>
                            
                            </a>
            
                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">Technology <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Dailiy technology articles <span class="danger">+472</span></div>

                            </a>

                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">UX design <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">UX design tips <span class="success">+472</span></div>

                            </a>

                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">Interaction tips <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Interaction articles <span class="warning">+472</span></div>

                            </a>

                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">App development <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Mobile development articles <span class="warning">+472</span></div>

                            </a>

                        </li>
                        <li>

                            <a href="##">

                                <div class="top-cat-list__title">Nature <span>8.2k</span></div>
                                <div class="top-cat-list__subtitle">Wildlife animal articles <span class="warning">+472</span></div>

                            </a>

                        </li>

                    </ul>

                </article>

                <article class="customers-wrapper">

                    <canvas id="customersChart1" aria-label="Customers statistics" role="img"></canvas>

                </article>

            </div>

        </div>

        <div class="row">

            <!-- Doughnut chart card -->

            <div class="col-lg-4">

                <div class="col-span-4 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }">
                    <!-- Card header -->
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                      <h4 class="text-lg font-semibold text-gray-500 dark:text-primary-light">Doughnut Chart</h4>
                      <div class="flex items-center">
                        <button
                          class="relative focus:outline-none"
                          x-cloak
                          @click="isOn = !isOn; updateDoughnutChart(isOn)"
                        >
                          <div
                            class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker"
                          ></div>
                          <div
                            class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                            :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isOn, 'translate-x-6 bg-primary-light dark:bg-primary': isOn }"
                          ></div>
                        </button>
                      </div>
                    </div> 
                    <!-- Chart -->
                    <div class="relative p-4 h-72">
                      <canvas id="doughnutChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Bar chart card -->

            <div class="col-lg-8">

                <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }">
                    <!-- Card header -->
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                      <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Bar Chart</h4>
                      <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-light">Last year</span>
                        <button
                          class="relative focus:outline-none" x-cloak 
                          @click="isOn = !isOn; $parent.updateBarChart(isOn)"
                          >
                          <div
                            class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker"
                          ></div>
                          <div
                            class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                            :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isOn, 'translate-x-6 bg-primary-light dark:bg-primary': isOn }"
                          ></div>
                        </button>
                      </div>
                    </div>
                    <!-- Chart -->
                    <div class="relative p-4 h-72">
                      <canvas id="barChart"></canvas>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-8 grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">

                <div class="col-span-2 bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }">
                    <!-- Card header -->
                    <div class="flex items-center justify-between p-4 border-b dark:border-primary">
                      <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Line Chart</h4>
                      <div class="flex items-center">
                        <button class="relative focus:outline-none" x-cloak @click="isOn = !isOn; $parent.updateLineChart()">
                          <div
                            class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker"
                          ></div>
                          <div
                            class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                            :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isOn, 'translate-x-6 bg-primary-light dark:bg-primary': isOn }"
                          ></div>
                        </button>
                      </div>
                    </div>
                    <!-- Chart -->
                    <div class="relative p-4 h-72">
                      <canvas id="lineChart"></canvas>
                    </div>
                  </div>

            </div>

            <div class="col-lg-4 grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">

                <div class="col-span-1 bg-white rounded-md dark:bg-darker">
                    <!-- Card header -->
                    <div class="p-4 border-b dark:border-primary">
                      <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Active users right now</h4>
                    </div>
                    <p class="p-4">
                      <span class="text-2xl font-medium text-gray-500 dark:text-light" id="usersCount">0</span>
                      <span class="text-sm font-medium text-gray-500 dark:text-primary">Users</span>
                    </p>
                    <!-- Chart -->
                    <div class="relative p-4">
                      <canvas id="activeUsersChart"></canvas>
                    </div>
                  </div>

            </div>

        </div>
    
    @endsection