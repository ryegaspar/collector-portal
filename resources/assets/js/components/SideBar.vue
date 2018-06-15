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
            <!--<ul class="nav">-->
                <!--<li class="nav-item">-->
                    <!--<a class="nav-link" href="index.html">-->
                        <!--<i class="icon-speedometer"></i>-->
                        <!--Item 1-->
                    <!--</a>-->
                <!--</li>-->
                <!--<li class="nav-item nav-dropdown">-->
                    <!--<a class="nav-link nav-dropdown-toggle" href="#">-->
                        <!--<i class="icon-cursor"></i> Item 2-->
                    <!--</a>-->
                    <!--<ul class="nav-dropdown-items">-->
                        <!--<li class="nav-item">-->
                            <!--<a class="nav-link" href="buttons-buttons.html">-->
                                <!--<i class="icon-cursor"></i> Item 2 - A-->
                            <!--</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                            <!--<a class="nav-link" href="buttons-button-group.html">-->
                                <!--<i class="icon-cursor"></i> Item 2 - B-->
                            <!--</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                            <!--<a class="nav-link" href="buttons-dropdowns.html">-->
                                <!--<i class="icon-cursor"></i> Item 2 - C-->
                            <!--</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                            <!--<a class="nav-link" href="buttons-loading-buttons.html">-->
                                <!--<i class="icon-cursor"></i> Item 2 - D-->
                            <!--</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                            <!--<a class="nav-link" href="buttons-social-buttons.html">-->
                                <!--<i class="icon-cursor"></i> Item 2 - E-->
                            <!--</a>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</li>-->


                <!--<li class="nav-item nav-dropdown">-->
                    <!--<a class="nav-link nav-dropdown-toggle" href="#">-->
                        <!--<i class="icon-layers"></i> Item 3-->
                    <!--</a>-->
                    <!--<ul class="nav-dropdown-items">-->
                        <!--<li class="nav-item nav-dropdown">-->
                            <!--<a class="nav-link nav-dropdown-toggle" href="#">-->
                                <!--<i class="icon-speech"></i> Item 3 - A-->
                            <!--</a>-->
                            <!--<ul class="nav-dropdown-items">-->
                                <!--<li class="nav-item">-->
                                    <!--<a class="nav-link" href="UIkits-invoicing-invoice.html">-->
                                        <!--<i class="icon-speech"></i> Item 3 - A - 1-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</li>-->
                        <!--<li class="nav-item nav-dropdown">-->
                            <!--<a class="nav-link nav-dropdown-toggle" href="#">-->
                                <!--<i class="icon-speech"></i> Item 3 - B-->
                            <!--</a>-->
                            <!--<ul class="nav-dropdown-items">-->
                                <!--<li class="nav-item">-->
                                    <!--<a class="nav-link" href="UIkits-email-inbox.html">-->
                                        <!--<i class="icon-speech"></i> Item 3 - B - 1-->
                                    <!--</a>-->
                                <!--</li>-->
                                <!--<li class="nav-item">-->
                                    <!--<a class="nav-link" href="UIkits-email-message.html">-->
                                        <!--<i class="icon-speech"></i> Item 3 - B - 2-->
                                    <!--</a>-->
                                <!--</li>-->
                                <!--<li class="nav-item">-->
                                    <!--<a class="nav-link" href="UIkits-email-compose.html">-->
                                        <!--<i class="icon-speech"></i> Item 3 - B - 3-->
                                    <!--</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</li>-->
            <!--</ul>-->
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
</template>

<script>
    export default {
    	data() {
    		return {
				menus: {
					item1: {
						href: '#',
						text: 'Item 1',
						icon: 'fa fa-desktop'
					},
                    item2: {
						href: '#',
                        text: 'Item 2',
                        icon: 'fa fa-desktop',
                        children: {
							item2A: {
								href: '#item2a',
                                icon: 'fa fa-wrench',
                                text: 'Item 2 - A',
                            },
                            item2B: {
								href: '#item2b',
                                icon: 'fa fa-pencil',
                                text: 'Item 2 - B'
                            },
                            item2C: {
								href: '#item2c',
                                icon: 'fa fa-angellist',
                                text: 'Item 2 - C'
                            }
                        }
                    },
                    item3: {
						href: '#',
                        text: 'Item 3',
                        icon: 'fa fa-desktop',
                        children: {
							item3A: {
								href: '#',
                                icon: 'fa fa-wrench',
                                text: 'Item 3 - A',
                                children: {
									item3A1: {
										href: '#item3A1',
                                        icon: 'fa fa-battery-three-quarters',
                                        text: 'Item 3 - A - 1'
                                    },
                                    item3A2: {
										href: '#item3A2',
                                        icon: 'fa fa-btc',
                                        text: 'Item 3 - A - 2'
                                    }
                                }
                            }
                        }
                    }
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