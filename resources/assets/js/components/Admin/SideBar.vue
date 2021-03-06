<template>
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item"
                    :class="hasChildren(menu) ? 'nav-dropdown': ''"
                    v-if="canShow(menu)"
                    v-for="menu in menus">
                    <a class="nav-link"
                       :class="hasChildren(menu) ? 'nav-dropdown-toggle': ''"
                       :href="menu.href">
                        <i :class="menu.icon"></i>
                        {{ menu.text }}
                    </a>
                    <ul class="nav-dropdown-items" v-if="hasChildren(menu)">
                        <li class="nav-item" :class="hasChildren(submenu) ? 'nav-dropdown' : ''"
                            v-if="canShow(submenu)"
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
		props: [
			'perms'
        ],
		data() {
			return {
				permissions: JSON.parse(this.perms),

				menus: {
					dashboard: {
						href: '/admin/dashboard',
						text: 'Dashboard',
						icon: 'fa fa-tachometer'
					},
                    Letters: {
						href:'#',
                        text: 'Letter Requests',
                        icon: 'icon-envelope',
                        children: {
							LetterRequest: {
								href: '/admin/letter-requests',
                                icon: 'icon-envelope',
                                text: 'Letter Requests'
                            },
							LetterRequestType: {
								href: '/admin/letter-request-type',
                                icon: 'icon-layers',
                                text: 'Letter Request Types',
                                permission: 'read letter-request-type'
                            }
                        }
					},
					CollectionSupport: {
						href:'#',
                        text: 'Collection Support',
                        icon: 'fa fa-arrow-circle-o-right',
                        children: {
							Adjustments: {
								href: '/admin/adjustments',
								text: 'Collector Adjustments',
								icon: 'fa fa-line-chart',
								permission: 'read adjustment'
							},
							Desk_Transfers: {
								href: '/admin/desk-transfer-requests',
								text: 'Desk Transfers',
								icon: 'fa fa-arrow-circle-o-right'
							},
                        }
					},
					Correspondence_Log: {
						href: '/admin/correspondence-log',
						text: 'Correspondence Log',
						icon: 'fa fa-comments'
					},
					Remittance_Log: {
						href: '/admin/remittance-log',
						text: 'Remittance Log',
						icon: 'fa fa fa-money',
						permission: 'read calendar'
					},
					Reports: {
						href:'#',
                        text: 'Reports',
                        icon: 'fa fa-file-text-o',
                        children: {
							Client_Reports: {
								href: '/admin/clientreports',
								text: 'Client Reporting',
								icon: 'fa fa-file-text-o',
								permission: 'read calendar'
							},
							OperationalReports: {
								href: '/admin/operationalreports',
								text: 'Operational Reports',
								icon: 'fa fa-apple'
							},
                        }
					},
                    Scripts: {
						href: '#',
                        text: 'Scripts',
                        icon: 'fa fa-pencil-square-o',
                        permission: 'read script',
                        children: {
                        	ScriptCreate: {
                        		href: '/admin/scripts/create',
                                icon: 'fa fa-plus',
                                text: 'Create Script',
                                permission: 'create script'
                            },
                            ScriptList: {
                        		href: '/admin/scripts',
								icon: 'fa fa-list-ol',
                                text: "Lists",
                                permission: 'read script'
							}
                        }

                    },
                    Closures: {
						href: '#',
                        text: 'Closures',
                        icon: 'fa fa-times-circle-o',
                        permission: 'view closure-report',
                        children: {
							ClosedAccountsPdc: {
								href: '/admin/closures/closed-accounts-pdc',
                                icon: 'fa fa-cc',
                                text: 'Closed Accounts PDC'
                            },
							SifClosures: {
								href: '/admin/closures/sif-closures',
                                icon: 'fa fa-minus-circle',
                                text: 'SIF Closures',
                            },
                            Recalls: {
								href: '/admin/closures/recalls',
                                icon: 'fa fa-times',
                                text: 'Recalls'
                            }
                        }
                    },
                    Collectors: {
						href: '#',
                        text: 'Collectors',
                        icon: 'fa fa-users',
                        permission: 'read collector',
                        children: {
							Collectors: {
								href: '/admin/collectors',
                                icon: 'fa fa-user',
                                text: 'Collectors',
								permission: 'read collector',
							},
                            BatchCollectors: {
								href: '/admin/collector-batches',
                                icon: 'fa fa-stack-exchange',
                                text: 'Collector Batches',
                                permission: 'read collector-batch'
                            }
                        }
                    },
					Admins: {
						href: '/admin/admins',
						text: 'Admins',
						icon: 'fa fa-user-secret',
						permission: 'read admin'
					},
					Calendars: {
						href: '/admin/calendars',
						text: 'Calendars',
						icon: 'fa fa-calendar',
						permission: 'read calendar'
					},
                    Settings: {
						href: '#',
                        text: 'Settings',
                        icon: 'fa fa-cog',
                        permission: 'read roles_permission',
                        children: {
							RolesPermissions: {
								href: '/admin/roles-permissions',
								text: 'Roles & Permissions',
								icon: 'fa fa-lock',
								permission: 'read roles_permission'

							},
							Sites: {
								href: '#',
								text: 'Unifin Sites',
								icon: 'fa fa-sitemap',
								permission: 'read site',
								children: {
									Site: {
										href: '/admin/sites',
										icon: 'fa fa-object-group',
										text: 'Sites'
									},
									SubSite: {
										href: '/admin/sub-sites',
										icon: 'fa fa-object-ungroup',
										text: 'Sub Sites'
									}
								}
							}
                        }
                    },
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
			},

            canShow(menu) {
				if (menu.hasOwnProperty('permission')) {
					return this.permissions.includes(menu.permission)
				} else
					return true;
            }
		}
	}
</script>