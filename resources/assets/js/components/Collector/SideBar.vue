<template>
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item"
                    :class="hasChildren(menu) ? 'nav-dropdown': ''"
                    v-for="menu in menus">
                    <a class="nav-link"
                       :class="hasChildren(menu) ? 'nav-dropdown-toggle': ''"
                       :href="menu.href">
                        <i :class="menu.icon"></i>
                        {{ menu.text }}
                    </a>
                    <ul class="nav-dropdown-items" v-if="hasChildren(menu)">
                        <li class="nav-item" :class="hasChildren(submenu) ? 'nav-dropdown' : ''"
                            v-for="submenu in menu.children">
                            <a class="nav-link" :class="hasChildren(submenu) ? 'nav-dropdown-toggle': ''"
                               :href="submenu.href">
                                <i :class="submenu.icon"></i> {{ submenu.text }}
                            </a>
                            <ul class="nav-dropdown-items" v-if="hasChildren(submenu)">
                                <li class="nav-item" v-for="subsubmenu in submenu.children">
                                    <a class="nav-link" :href="subsubmenu.href">
                                        <i :class="subsubmenu.icon"></i> {{ subsubmenu.text }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
</template>

<script>
    export default {
    	data() {
    		return {
				menus: {
					dashboard: {
						href: 'dashboard',
						text: 'Dashboard',
						icon: 'fa fa-tachometer'
					},
                    accounts: {
						href: 'accounts',
                        text: 'Accounts',
                        icon: 'fa fa-users'
                    },
                    transactions: {
						href: 'transactions',
                        text: 'Transactions',
                        icon: 'fa fa-credit-card'
                    },
                    collectorPDC: {
						href: 'collectorPDC',
                        text: 'CollectorPDC',
                        icon: 'fa fa-apple'
                    },
                    adjustments: {
						href: 'adjustments',
                        text: 'Adjustments',
                        icon: 'fa fa-line-chart'
                    },
                    letterRequests: {
						href: 'letter-requests',
                        text: 'Letter Requests',
                        icon: 'icon-envelope'
                    },
                    scripts: {
						href: 'scripts',
                        text: 'Scripts',
                        icon: 'fa fa-pencil-square-o'
                    }
                    // item2: {
						// href: '#',
                    //     text: 'Item 2',
                    //     icon: 'fa fa-desktop',
                    //     children: {
						// 	item2A: {
						// 		href: '#item2a',
                    //             icon: 'fa fa-wrench',
                    //             text: 'Item 2 - A',
                    //         },
                    //         item2B: {
						// 		href: '#item2b',
                    //             icon: 'fa fa-pencil',
                    //             text: 'Item 2 - B'
                    //         },
                    //         item2C: {
						// 		href: '#item2c',
                    //             icon: 'fa fa-angellist',
                    //             text: 'Item 2 - C'
                    //         }
                    //     }
                    // },
                    // item3: {
						// href: '#',
                    //     text: 'Item 3',
                    //     icon: 'fa fa-desktop',
                    //     children: {
						// 	item3A: {
						// 		href: '#',
                    //             icon: 'fa fa-wrench',
                    //             text: 'Item 3 - A',
                    //             children: {
						// 			item3A1: {
						// 				href: '#item3A1',
                    //                     icon: 'fa fa-battery-three-quarters',
                    //                     text: 'Item 3 - A - 1'
                    //                 },
                    //                 item3A2: {
						// 				href: '#item3A2',
                    //                     icon: 'fa fa-btc',
                    //                     text: 'Item 3 - A - 2'
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
				}
            }
        },

        methods: {
    		hasChildren(menu) {
    			return menu.hasOwnProperty('children');
            }
        }
    }
</script>