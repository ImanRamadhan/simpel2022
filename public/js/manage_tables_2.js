(function(table_support2, $) {

	var enable_actions = function(callback) {
		return function() {
			var selection_empty = selected_rows().length == 0;
			$("#toolbar2 button:not(.dropdown-toggle)").attr('disabled', selection_empty);
			typeof callback == 'function' && callback();
		}
	};

	var table = function() {
		return $("#table2").data('bootstrap.table');
	}

	var selected_ids = function () {
		return $.map(table().getSelections(), function (element) {
			return element[options.uniqueId || 'id'] !== '-' ? element[options.uniqueId || 'id'] : null;
		});
	};

	var selected_rows = function () {
		return $("#table2 td input:checkbox:checked").parents("tr");
	};

	var row_selector = function(id) {
		return "tr[data-uniqueid='" + id + "']";
	};

	var rows_selector = function(ids) {
		var selectors = [];
		ids = ids instanceof Array ? ids : ("" + ids).split(":");
		$.each(ids, function(index, element) {
			selectors.push(row_selector(element));
		});
		return selectors;;
	};

	var highlight_row = function (id, color) {
		$(rows_selector(id)).each(function(index, element) {
			var original = $(element).css('backgroundColor');
			$(element).find("td").animate({backgroundColor: color || '#e1ffdd'}, "slow", "linear")
				.animate({backgroundColor: color || '#e1ffdd'}, 5000)
				.animate({backgroundColor: original}, "slow", "linear");
		});
	};

	var do_delete = function (url, ids) {
		if (confirm($.fn.bootstrapTable.defaults.formatConfirmDelete())) {
			$.post((url || options.resource) + '/delete', {'ids[]': ids || selected_ids()}, function (response) {
				//delete was successful, remove checkbox rows
				if (response.success) {
					var selector = ids ? row_selector(ids) : selected_rows();
					table().collapseAllRows();
					$(selector).each(function (index, element) {
						$(this).find("td").animate({backgroundColor: "green"}, 1200, "linear")
							.end().animate({opacity: 0}, 1200, "linear", function () {
								table().remove({
									field: options.uniqueId,
									values: selected_ids()
								});
								if (index == $(selector).length - 1) {
									refresh();
									enable_actions();
								}
							});
					});
					$.notify(response.message, { type: 'success' });
				} else {
					$.notify(response.message, { type: 'danger' });
				}
			}, "json");
			
		} else {
			return false;
		}
	};

	var do_restore = function (url, ids) {
		if (confirm($.fn.bootstrapTable.defaults.formatConfirmRestore())) {
			$.post((url || options.resource) + '/restore', {'ids[]': ids || selected_ids()}, function (response) {
				//restore was successful, remove checkbox rows
				if (response.success) {
					var selector = ids ? row_selector(ids) : selected_rows();
					table().collapseAllRows();
					$(selector).each(function (index, element) {
						$(this).find("td").animate({backgroundColor: "green"}, 1200, "linear")
							.end().animate({opacity: 0}, 1200, "linear", function () {
							table().remove({
								field: options.uniqueId,
								values: selected_ids()
							});
							if (index == $(selector).length - 1) {
								refresh();
								enable_actions();
							}
						});
					});
					$.notify(response.message, { type: 'success' });
				} else {
					$.notify(response.message, { type: 'danger' });
				}
			}, "json");
		} else {
			return false;
		}
	};

	var load_success = function(callback) {
		return function(response) {
			typeof options.load_callback == 'function' && options.load_callback();
			options.load_callback = undefined;
			dialog_support.init("a.modal-dlg");
			typeof callback == 'function' && callback.call(this, response);
		}
	};

	var options;

	var toggle_column_visibility = function() {
		if (localStorage[options.employee_id]) {
			var user_settings = JSON.parse(localStorage[options.employee_id]);
			user_settings[options.resource] && $.each(user_settings[options.resource], function(index, element) {
				element ? table().showColumn(index) : table().hideColumn(index);
			});
		}
	};

	var init = function (_options) {
		options = _options;
		enable_actions = enable_actions(options.enableActions);
		load_success = load_success(options.onLoadSuccess);
		$('#table2').bootstrapTable($.extend(options, {
			columns: options.headers,
			stickyHeader: true,
			url: options.resource + '/search',
			sidePagination: 'server',
			pageSize: options.pageSize,
			striped: true,
			pagination: true,
			search: options.resource || false,
			showColumns: true,
			clickToSelect: true,
			showExport: true,
			exportDataType: 'all',
			exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
			exportOptions: {
				fileName: options.resource.replace(/.*\/(.*?)$/g, '$1')
			},
			onPageChange: function(response) {
				load_success(response);
				enable_actions();
			},
			toolbar: '#toolbar2',
			uniqueId: options.uniqueId || 'id',
			trimOnSearch: false,
			onCheck: enable_actions,
			onUncheck: enable_actions,
			onCheckAll: enable_actions,
			onUncheckAll: enable_actions,
			onLoadSuccess: function(response) {
				load_success(response);
				enable_actions();
			},
			onColumnSwitch : function(field, checked) {
				var user_settings = localStorage[options.employee_id];
				user_settings = (user_settings && JSON.parse(user_settings)) || {};
				user_settings[options.resource] = user_settings[options.resource] || {};
				user_settings[options.resource][field] = checked;
				localStorage[options.employee_id] = JSON.stringify(user_settings);
			},
			queryParamsType: 'limit',
			iconSize: 'sm',
			silentSort: true,
			paginationVAlign: 'bottom',
			escape: false
		}));
		enable_actions();
		init_delete();
		init_restore();
		toggle_column_visibility();
		dialog_support.init("button.modal-dlg");
	};

	var init_delete = function (confirmMessage) {
		$("#delete2").click(function (event) {
			do_delete();
		});
	};

	var init_restore = function (confirmMessage) {
		$("#restore2").click(function (event) {
			do_restore();
		});
	};

	var refresh = function() {
		table().refresh();
	}

	var submit_handler = function(url) {
		return function (resource, response) {
			
			var id = response.id;
			if (!response.success) {
				$.notify(response.message, { type: 'danger' });
			} else {
				var message = response.message;
				var selector = rows_selector(response.id);
				var rows = $(selector.join(",")).length;
				if (rows > 0 && rows < 15) {
					var ids = response.id.split(":");
					$.get([url || resource + '/get_row', id].join("/"), {}, function (response) {
						$.each(selector, function (index, element) {
							var id = $(element).data('uniqueid');
							table().updateByUniqueId({id: id, row: response[id] || response});
						});
						dialog_support.init("a.modal-dlg");
						highlight_row(ids);
					}, 'json');
				} else {
					// call hightlight function once after refresh
					options.load_callback = function () {
						enable_actions();
						highlight_row(id);
					};
					refresh();
				}
				
				$.notify(message, {type: 'success' });
				
			}
			return false;
		};
	};

	var handle_submit = submit_handler();

	$.extend(table_support2, {
		submit_handler: function(url) {
			this.handle_submit = submit_handler(url);
		},
		handle_submit: handle_submit,
		init: init,
		do_delete: do_delete,
		do_restore: do_restore,
		refresh : refresh,
		selected_ids : selected_ids,
	});

})(window.table_support2 = window.table_support2 || {}, jQuery);
