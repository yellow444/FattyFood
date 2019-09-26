var addEvent = function(obj, evType, fn)
{
	if (obj.addEventListener)
	{
		obj.addEventListener(evType, fn, false);
	}
	else if (obj.attachEvent)
	{
		obj.attachEvent('on' + evType, fn);
	};
};
var in_array = function(val, array)
{
	for (var i = 0; i < array.length; i++)
	{
		if (array[i] == val)
		{
			return true;
		}
	}
	return false;
};
var delete_in_array = function(val, array)
{
	var array_end = [];
	for (var i = 0; i < array.length; i++)
	{
		if (array[i] == val)
		{
			array_end = array.splice(i + 1);
			array.pop();
			break;
		}
	}
	return array.concat(array_end);
};
var textarea_resizable_grippie = false;

// Objets : (d�)coche une case � cocher.
function select(e, all)
{
	if (e.prop('disabled'))
	{
		return;
	}
	if (e.prop('checked') && !all)
	{
		e.prop('checked', false);
		e.parents('.selectable_class').addClass('unselected').removeClass('selected');
	}
	else
	{
		e.prop('checked', true);
		e.parents('.selectable_class').addClass('selected').removeClass('unselected');
	}
}

// Selectionne tous les objets.
function select_all()
{
	$('.selectable').each(function()
	{
		select($(this), 1);
	});
}

// Inverse la s�lection des objets.
function select_invert()
{
	$('.selectable').each(function()
	{
		select($(this), 0);
	});
}

// Montre les details de tous les objets.
function show_all()
{
	if ($('#show_mode').length > 0)
	{
		var sel = $('#show_mode :selected').val();
		$(sel).show();
		return;
	}
	$('.item_fold,.obj_fold,.obj_w_fold').show();
}

// Cache les details de tous les objets.
function hide_all()
{
	if ($('#show_mode').length > 0)
	{
		$($('#show_mode :selected').val()).hide();
		return;
	}
	$('.item_fold,.obj_fold,.obj_w_fold').hide();
}

// Montre ou cache un �l�ment selon son �tat.
function showhide(sel)
{
	if ($(sel).is(':hidden'))
	{
		$(sel).show();
	}
	else
	{
		$(sel).hide();
	}
}

// Montre ou cache la zone d'�dition d'un objet de type widget.
function w_edition()
{
	var id = $(this).parents('.obj_w').attr('id');
	if ($('#obj_w_edition_' + id).is(':hidden'))
	{
		$('#obj_w_edition_' + id).slideDown('normal', function()
		{
			$('#obj_w_edition_' + id + ' div p:visible:first input').focus();
		});
	}
	else
	{
		$('#obj_w_edition_' + id).slideUp('normal');
	}
}

// Liens externes.
var external_links = function()
{
	$('a.ex').click(function()
	{
		var url = $(this).attr('href')
		if (url.match(/^(ftp|https?):/))
		{
			$(this).attr('href', gallery_path + '/link.php?url=' + url);
		}
	});
};



// G�n�ral.
jQuery(function($)
{
	$('#form_new_thumb dl a').mouseover(function()
	{
		$(this).css('cursor', 'pointer');
	});

	// �tiquette HTML.
	$('textarea')
		.focus(function()
		{
			$(this).parents('.field_html').find('.field_html_tag a').addClass('f_html_active');
		})
		.blur(function()
		{
			$(this).parents('.field_html').find('.field_html_tag a').removeClass('f_html_active');
		});

	// Message de d�marrage.
	$('#dashboard_start_hide_link').click(function()
	{
		$('#dashboard_start_hide_form input[type="submit"]').click();
	});

	// Focus sur un �l�ment au chargement de la page.
	$('.onload_focus:visible:first').focus();

	// Liens externes.
	external_links();

	// Lien de d�connexion.
	$('#deconnect_link').click(function()
	{
		$('#deconnect_form input[type="submit"]').click();
	});

	// Textarearesizer, sauf pour Webkit et Gecko >= 2.
	if (!$.browser.webkit && !($.browser.mozilla && parseInt($.browser.version) >= 2))
	{
		$('textarea.resizable:not(.processed)').TextAreaResizer();
		$('div.grippie').mousedown(function()
		{
			if ($('#help_textarea_html').is('div'))
			{
				textarea_resizable_grippie = true;
				$('.field_html_tag a[rel="' + $('#help_textarea_html').attr('class') + '"]')
					.trigger('click');
			}
		});
	}

	// Supprime la d�sactivation des boutons de formulaire pour
	// forcer leur utilisation seulement si Javascript est activ�.
	$('input.submit.js_required').prop('disabled', false);

	// Erreur sur un champ de formulaire.
	if (typeof field_error != 'undefined')
	{
		$('label[for=' + field_error + ']').parents('p.field').addClass('field_error');
	}

	// Tablesorter.
	if ($('.sorter').is('table'))
	{
		$('table.sorter').tablesorter({
			sortInitialOrder: 'desc',
			sortList: [[0,0]]
		});
	}

	// Affichage d'�l�ments de la page courante.
	$('.show_parts').click(function()
	{
		var id = '#' + $(this).find('a').attr('rel');
		if ($(id).is(':hidden'))
		{
			$('#obj_' + id.replace(/^.*_(\d+)$/, '$1') + ' .obj_fold').hide();
			$(id).show(0, function()
			{
				$(id + ' p:visible:first input').focus();
			});
		}
		else
		{
			$(id).hide();
		}
	});

	// Affichage d'options (filtres, recherche, etc.).
	$('.show_tool').click(function()
	{
		var id = '#' + $(this).find('a').attr('rel');
		if ($(id).is(':hidden'))
		{
			$('.tool').not($(id)).hide();
			$(id).show();
			$(id + ' .focus').focus();
		}
		else
		{
			$(id).hide();
		}
	});

	// Affichage d'une partie de la page avec une case � cocher.
	$('input[type="checkbox"].show_part').each(function()
	{
		if ($(this).prop('checked'))
		{
			$('#' + $(this).attr('rel')).show();
		}
		else
		{
			$('#' + $(this).attr('rel')).hide();
		}
	});
	$('input[type="checkbox"].show_part').change(function()
	{
		if ($(this).prop('checked'))
		{
			$('#' + $(this).attr('rel')).show();
		}
		else
		{
			$('#' + $(this).attr('rel')).hide();
		}
	});

	// Options communes de filigrane.
	if ($('#watermark_options_common').is('div'))
	{
		if ($('#watermark_specific').prop('checked'))
		{
			$('#watermark_options_common').show();
		}
		else
		{
			$('#watermark_options_common').hide();
		}
		$('#watermark_none').change(function()
		{
			if ($(this).prop('checked'))
			{
				$('#watermark_options_common').hide();
			}
		});
		$('#watermark_default').change(function()
		{
			if ($(this).prop('checked'))
			{
				$('#watermark_options_common').hide();
			}
		});
		$('#watermark_specific').change(function()
		{
			if ($(this).prop('checked'))
			{
				$('#watermark_options_common').show();
			}
		});
	}

	// Correction du num�ro de page dans l'URL apr�s
	// une action ayant modifi� le nombre d'objets d'une section.
	if (window.location.href.search(/\/page\/(\d+)$/) != -1)
	{
		var url = window.location.href;
		if ($('select[name="page"]').is('select'))
		{
			var urlpage = url.replace(/^.+\/page\/(\d+)$/, '$1');
			var lastpage = $('select[name="page"]').children('option:last-child').attr('value');
			if (parseInt(urlpage) > parseInt(lastpage.replace(/^\/page\/(\d+)$/, '$1')))
			{
				history.pushState(null, null, url.replace(/^(.+)\/page\/\d+$/, '$1' + lastpage));
			}
		}
		else
		{
			history.pushState(null, null, url.replace(/^(.+)\/page\/\d+$/, '$1'));
		}
	}

	// Confirmation de suppression.
	$('form#confirm_delete').submit(function()
	{
		return confirm(confirm_delete);
	});

	// S�lection d'objets.
	if ($('body').attr('id') != 'section_group_functions')
	{
		// Initialisation.
		$('.selectable').prop('checked', false);
		$('.unselected .selectable').prop('checked', false);
		$('.selected .selectable').prop('checked', true);

		// Case � cocher.
		$('.selectable').click(function()
		{
			select($(this), 0);
		});

		// Zone de s�lection.
		$('.selectable_zone').click(function()
		{
			select($(this).find('.selectable'), 0);
		});
	}

	// Pour la s�lection, on affiche la liste secondaire selon le type d'action choisi.
	$('#selection_action').change(function()
	{
		if ($(this).children('option[value="move"]').prop('selected'))
		{
			$('#categories').show();
		}
		else
		{
			$('#categories').hide();
		}
		if ($(this).children('option[value="hits"]').prop('selected'))
		{
			$('#hits').show();
		}
		else
		{
			$('#hits').hide();
		}
		if ($(this).children('option[value="add_tags"]').prop('selected'))
		{
			$('#add_tags').show();
		}
		else
		{
			$('#add_tags').hide();
		}
		if ($(this).children('option[value="remove_tags"]').prop('selected'))
		{
			$('#remove_tags').show();
		}
		else
		{
			$('#remove_tags').hide();
		}
		if ($(this).children('option[value="owner"]').prop('selected'))
		{
			$('#users').show();
		}
		else
		{
			$('#users').hide();
		}
		if ($(this).children('option[value="group"]').prop('selected'))
		{
			$('#list_groups').show();
		}
		else
		{
			$('#list_groups').hide();
		}
	});

	// Tri des cat�gories et images.
	if ($('body').attr('id') == 'section_sort_album'
	 || $('body').attr('id') == 'section_sort_category')
	{
		$('#sort_thumbs').sortable(
		{
			containment: '#content',
			items: 'dl',
			opacity: 0.5
		});
		$('input[name="sort"]').click(function()
		{
			$('#serial').val($('#sort_thumbs').sortable('serialize'));
		});
	}

	// Suppression des widgets et des pages.
	if ($('body').attr('id') == 'section_widgets'
	 || $('body').attr('id') == 'section_pages')
	{
		$('.icon_delete').click(function()
		{
			if (confirm(confirm_delete))
			{
				var name = $(this).parents('.obj_w').find('input[type="hidden"]')
					.attr('name').replace(/w\[(.+)\]/, '$1');
				$(this).parents('.obj_w_inner').append(
					'<input type="hidden" name="w[' + name + '][delete]" value="1" />'
				);
				$(this).parents('.obj_w').hide();
			}
		});
	}

	// Objets de type widgets.
	if ($('#obj_w').is('form') || $('#obj_w').is('div'))
	{
		// Tri.
		$('#obj_w').sortable(
		{
			containment: '#content',
			items: '.obj_w',
			handle: '.obj_w_sortable',
			opacity: 0.5
		});
		$('input.submit').click(function()
		{
			$('#serial').val($('#obj_w').sortable('serialize'));
			$('#obj_w').submit();
		});

		// �dition.
		$('#obj_w .obj_w_edit .icon_edit').click(w_edition);
	}

	// Retaillage des vignettes.
	if ($('body').attr('id') == 'section_thumb_category'
	|| $('body').attr('id') == 'section_thumb_image'
	|| $('body').attr('id') == 'section_new_thumb')
	{
		if ($('.report_crop').is('p'))
		{
			$('#current img').fadeOut().fadeIn('slow');
		}
	}

	// Nouvelle vignette.
	if ($('body').attr('id') == 'section_new_thumb')
	{
		$('.thumbs dt').each(function()
		{
			$(this).click(function()
			{
				$(this).children('input').prop('checked', true);
				$('#form_new_thumb').submit();
			});
		});
	}

	// Confirmation de suppression des commentaires, logs, votes, tags et groupes.
	if ($('body').attr('id') == 'section_comments_images'
	 || $('body').attr('id') == 'section_camera_brands'
	 || $('body').attr('id') == 'section_camera_models'
	 || $('body').attr('id') == 'section_votes'
	 || $('body').attr('id') == 'section_logs'
	 || $('body').attr('id') == 'section_votes'
	 || $('body').attr('id') == 'section_tags'
	 || $('body').attr('id') == 'section_groups')
	 {
		var submit = true;

		$('#action_submit').click(function()
		{
			if ($(this).attr('name') != 'selection')
			{
				return;
			}

			// Confirmation sur la suppression de commentaires.
			if ($('#selection_action').children('option[value="delete"]').prop('selected'))
			{
				submit = confirm(confirm_delete);
				return;
			}
		});

		// Envoi du formulaire.
		$('#form_edit').submit(function()
		{
			if (submit)
			{
				return true;
			}
			else
			{
				submit = true;
				return false;
			}
		});
	}

});

// Aide contextuelle.
jQuery(function($)
{
	if (!$('#help').is('div'))
	{
		return;
	}
	var help_html;
	var help_id;
	var help_content = '';
	$('.help_link').click(function()
	{
		help_id = $(this).parent().is('#content')
			? 'help_page'
			: 'help_' + $(this).attr('rel');
		if ($('#' + help_id).is('div'))
		{
			if ($('#' + help_id).is(':hidden'))
			{
				$('#' + help_id).show();
			}
			else
			{
				$('#' + help_id).hide();
			}
		}
		else
		{
			if ($(this).parent().is('#content'))
			{
				$('.h_item').each(function(){
					help_content += $(this).html();
				});
			}
			else
			{
				help_content = $('#' + $(this).attr('rel')).html()
			}
			help_html = '<div style="display:none" class="help_context"'
				+ ' id="' + help_id + '">'
				+ '<div class="help_title">'
				+ '<a href="javascript:;"></a>'
				+ '<span class="icon icon_help">' + $('#help_title').text() + '</span>'
				+ '</div><div class="help_content">'
				+ help_content
				+ '</div></div>';
			if ($(this).parent().is('#content'))
			{
				$('h2:first').after(help_html);
				$('#' + help_id).css('marginLeft', 'auto');
			}
			else if ($(this).parent().is('h3') || $(this).parent().is('legend'))
			{
				$(this).parent().after(help_html);
			}
			else
			{
				$(this).after(help_html);
			}
			$('#' + help_id + ' h3:first').css('marginTop', 0);
			$('#' + help_id + ' h4:first').css('marginTop', 0);
			$('#' + help_id + ' h3 + h4').css('marginTop', 0);
			if (!$(this).parent().is('#content'))
			{
				$('#' + help_id + ' .help_title span').append(
					' : ' + $('#' + help_id).find('h3').text()
				);
				$('#' + help_id + ' h3').remove();
			}
			$('#' + help_id).css('opacity', 0.9).show();
			$('#' + help_id + ' .help_title a').click(function()
			{
				$(this).parents('.help_context').hide();
			});
			external_links();
		}
	});
});

// Aide contextuelle : balises HTML.
jQuery(function($)
{
	if (!$('.field_html_tag').is('span'))
	{
		return;
	}

	$('.field_html_tag a').click(function()
	{
		var textarea = $(this).parents('p').find('textarea');

		// Dimensions de l'aide contextuelle.
		var sizer = function(textarea)
		{
			// Les dimensions et la position de l'aide
			// doivent correspondre � celles du <textarea>.
			var help_border = parseInt($('#help_textarea_html').css('border-left-width'));
			var textarea_offset = textarea.offset();
			$('#help_textarea_html').css(
			{
				height: (textarea.outerHeight() - (help_border * 2)) + 'px',
				width: (textarea.outerWidth() - (help_border * 2)) + 'px'
			});

			// Ajustement de la hauteur du contenu de l'aide.
			var help_content_padding =
				parseInt($('#help_textarea_html .help_content').css('padding-top'));
			$('#help_textarea_html .help_content').css(
			{
				height: (textarea.outerHeight() - (help_border * 2)
					- $('#help_textarea_html .help_title').outerHeight()
					- help_content_padding * 2) + 'px'
			});
		};

		// Si l'aide existe d�j�, on la supprime.
		if ($('#help_textarea_html').is('span'))
		{
			var help_class = $('#help_textarea_html').attr('class')
				.replace(/\s*help_textarearesizer\s*/, '');
			var p = $('#help_textarea_html').parents('p');

			p.find('.field_html_tag a').removeClass('f_html_help');
			$('#help_textarea_html').remove();

			// Si l'aide ne correspond pas au <textarea> courant,
			// on simule un click pour afficher de nouveau l'aide
			// pour le <textarea> courant.
			if (help_class != $(this).attr('rel'))
			{
				$(this).trigger('click');
			}
			else if (!textarea_resizable_grippie)
			{
				p.find('textarea').focus();
			}
		}

		// Sinon on la cr�e.
		else
		{
			// Hauteur minimale de l'aide.
			if (textarea.outerHeight() < 350)
			{
				textarea.css('height', '350px');
			}

			textarea.before(
				'<span id="help_textarea_html">' +
				' <span class="help_title">' +
				'   <span class="icon icon_help">' + help_html_title + '</span>' +
				' </span>' +
				' <span class="help_content"></span>' +
				'</span>'
			);

			// Dimensions de l'aide contextuelle.
			sizer(textarea);
			$(window).resize(function()
			{
				sizer(textarea);
			});

			// On ajoute la liste des balises et attributs.
			$('#help_textarea_html .help_content').html(help_html_content);

			// Exception pour Textarearesizer;
			if (!$.browser.webkit && !($.browser.mozilla && parseInt($.browser.version) >= 2))
			{
				$('#help_textarea_html').addClass('help_textarearesizer');
			}

			// Identifiant.
			var rand = Math.random().toString().replace('.', '');
			$(this).attr('rel', rand);
			$('#help_textarea_html').addClass(rand);

			$('#help_textarea_html').parents('p').find('.field_html_tag a')
				.addClass('f_html_help');
		}
	});
});

// Langues d'�dition.
jQuery(function($)
{
	if (!$('#uhlink_langs').is('li'))
	{
		return;
	}

	var timeout_lang_list;

	$('#uhlink_langs,#lang_edition_list p').click(function()
	{
		if ($('#lang_edition_list').is(':hidden'))
		{
			$('#lang_edition_list').slideDown('fast');
		}
		else
		{
			$('#lang_edition_list').slideUp('fast');
		}
	});
	$('#uhlink_langs,#lang_edition_list')
		.bind('mouseleave', function()
		{
			timeout_lang_list = setTimeout(
				function() { $('#lang_edition_list').slideUp('fast'); },
				500
			);
		})
		.bind('mouseenter', function()
		{
			clearTimeout(timeout_lang_list);
		});
	$('.text,textarea')
		.bind('mouseenter', function()
		{
			timeout_lang_list = setTimeout(
				function(){ $('#lang_edition_list').slideUp('fast'); },
				500
			);
		});
	$('#lang_edition_list input').prop('checked', false);
	$('#lang_edition_list input.selected_lang').prop('checked', true);

	// Case � cocher.
	$('#lang_edition_list li input').click(function()
	{
		if ($(this).prop('checked'))
		{
			$(this).prop('checked', false);
		}
		else
		{
			$(this).prop('checked', true);
		}
	});

	// Zone de s�lection.
	$('#lang_edition_list li').click(function()
	{
		var lang = $(this).find('a').attr('rel');
		if ($(this).find('input').prop('checked'))
		{
			$(this).find('input').prop('checked', false);
			$('label.icon_' + lang).parents('p').hide();
		}
		else
		{
			$(this).find('input').prop('checked', true);
			$('label.icon_' + lang).parents('p').show();
		}

		// Enregistrement des langues dans les pr�f�rences de l'administrateur.
		var langs = [];
		$('#lang_edition_list li').each(function()
		{
			if ($(this).find('input').prop('checked'))
			{
				langs.push($(this).find('a').attr('rel'));
			}
		});
		$.post(
			gallery_path + '/ajax.php',
			{ section: 'langs-edition', langs: langs.join(',') }
		);
	});
});

// Tableau de bord.
jQuery(function($)
{
	if (!$('#section_dashboard').is('body'))
	{
		return;
	}

	// Navigation entre �l�ments d'un bloc.
	$(this).find('.dashboard_nav a').click(function()
	{
		var prev_or_next = $(this).attr('class').replace('dashboard_', '');
		var nb_items = $(this).parents('.dashboard_bloc')
			.find('.' + $(this).attr('rel')).length;
		var current = $(this).parents('.dashboard_bloc')
			.find('.' + $(this).attr('rel') + ':visible');
		var current_n = current.attr('id').replace($(this).attr('rel') + '_', '');
		var current_n_new = (prev_or_next == 'next' ?
				parseInt(current_n) + 1 : parseInt(current_n) - 1);

		// �l�ment courant.
		current.hide();
		$(this).parents('.dashboard_bloc')
			.find('#' + $(this).attr('rel') + '_' + current_n_new).show();

		// Position courante.
		$(this).parents('.dashboard_bloc').find('.dashboard_nav span')
			.text(current_n_new + '/' + nb_items);

		// Liens de navigation.
		if (current_n == 1)
		{
			$(this).parents('.dashboard_bloc').find('.dashboard_prev')
				.css('visibility', 'visible');
		}
		if (current_n == 2 && prev_or_next == 'prev')
		{
			$(this).parents('.dashboard_bloc').find('.dashboard_prev')
				.css('visibility','hidden');
		}
		if (parseInt(current_n) + 1 == nb_items && prev_or_next == 'next')
		{
			$(this).parents('.dashboard_bloc').find('.dashboard_next')
				.css('visibility', 'hidden');
		}
		if (current_n == nb_items)
		{
			$(this).parents('.dashboard_bloc').find('.dashboard_next')
				.css('visibility', 'visible');
		}
	});
});

// Section "Albums".
jQuery(function($)
{
	// Date de cr�ation.
	$('.date_reset').click(function()
	{
		$(this).parent().find('.date_title').prop('selected', true);
	});

	// Moteur de recherche.
	if ($('#search_categories').is('input'))
	{
		if ($('#search_categories').is(':checked'))
		{
			$('.search_images_fields').hide();
		}
		else
		{
			$('.search_categories_fields').hide();
		}
		$('#search_categories').click(function()
		{
			$('.search_images_fields').hide();
			$('.search_categories_fields').show();
		});
		$('#search_images').click(function()
		{
			$('.search_categories_fields').hide();
			$('.search_images_fields').show();
		});
	}

	// S�lection des images.
	if ($('#selection_action option[value="hits"]').is('option')
	|| $('body').attr('id') == 'section_images_pending')
	{
		var submit = true;

		// Sur la s�lection d'images.
		$('#action_submit').click(function()
		{
			if ($(this).attr('name') != 'selection')
			{
				return;
			}

			// Confirmation sur la suppression d'images.
			if ($('#selection_action').children('option[value="delete"]').prop('selected'))
			{
				submit = confirm(confirm_delete);
				return;
			}

			// On interdit la s�lection d'une cat�gorie destination pour
			// les d�placements d'images.
			$('#categories .ig_mc_type_category').each(function()
			{
				if ($(this).prop('selected') &&
				($('#selection_action').children('option[value="move"]').prop('selected')))
				{
					submit = false;
					alert(msg_destination_cat);
				}
			});
		});

		// Envoi du formulaire.
		$('#form_edit').submit(function()
		{
			if (submit)
			{
				return true;
			}
			else
			{
				submit = true;
				return false;
			}
		});

		// Ajout ou retrait de tags.
		$('#add_tags,#remove_tags').keydown(function(event)
		{
			if (event.keyCode == 13)
			{
				submit = false;
				$('#action_submit').click();
			}
		});
	}

	// Bo�tes.
	var timeout_box = [];
	var anim_speed = 200;
	var z_index_change = false;
	var close_box = function(box_id)
	{
		z_index_change = true;
		$('#' + box_id + ' .obj_banner_box_inner').fadeOut(anim_speed, function()
		{
			if (z_index_change)
			{
				$('.obj_banner_box').css('z-index', 4);
			}
		});
		$('#' + box_id + ' .obj_banner_box_link').removeClass('obj_banner_box_open');
	};
	var hide_box = function(box_id)
	{
		if ($('#' + box_id + ' .obj_banner_box_inner').is(':hidden'))
		{
			return;
		}
		clear_box(box_id);
		timeout_box[box_id] = setTimeout(function(){close_box(box_id);}, 500);
	};
	var clear_box = function(box_id)
	{
		clearTimeout(timeout_box[box_id]);
	};
	$('.obj_banner_box_inner')
		.bind('mouseenter', function(){clear_box($(this).parents('.obj_banner_box').attr('id'))})
		.bind('mouseleave', function(){hide_box($(this).parents('.obj_banner_box').attr('id'))});
	$('.obj_banner_box_link a')
		.bind('mouseenter', function(){clear_box($(this).parents('.obj_banner_box').attr('id'))})
		.bind('mouseleave', function(){hide_box($(this).parents('.obj_banner_box').attr('id'))});
	$('.obj_banner_box_link a,.obj_banner_box_title').click(function()
	{
		box_id = $(this).parents('.obj_banner_box').attr('id');
		z_index_change = false;
		for (bi in timeout_box)
		{
			clearTimeout(timeout_box[bi]);
		}
		if ($('#' + box_id + ' .obj_banner_box_inner').is(':hidden'))
		{
			// Fermeture de toutes les bo�tes.
			$('.obj_banner_box_inner').hide();
			$('.obj_banner_box_link').removeClass('obj_banner_box_open');

			// On place la bo�te courante devant toutes les autres.
			$('.obj_banner_box').css('z-index', 3);
			$('#' + box_id + '.obj_banner_box').css('z-index', 4);

			// Ouverture de la bo�te courante.
			$('#' + box_id + ' .obj_banner_box_inner').fadeIn(anim_speed);
			$('#' + box_id + ' .obj_banner_box_link').addClass('obj_banner_box_open');
		}
		else
		{
			close_box(box_id);
		}
	});
});

// Section "Albums", upload d'images.
jQuery(function($)
{
	if ($('body').attr('id') != 'section_album'	|| !$('#upload').is('div'))
	{
		return;
	}

	new Upload('upload_', upload_options);
});

// Section "Albums", s�lection de cat�gories.
jQuery(function($)
{
	if ($('body').attr('id') != 'section_category')
	{
		return;
	}

	var submit = true;

	// Sur la s�lection de cat�gories.
	$('#action_submit').click(function()
	{
		if ($(this).attr('name') != 'selection')
		{
			return;
		}

		// Confirmation sur la suppression de cat�gories.
		if ($('#selection_action').children('option[value="delete"]').prop('selected'))
		{
			submit = confirm(confirm_delete);
			return;
		}

		// R�gles des d�placements interdits de cat�gories.
		if ($('#selection_action').children('option[value="move"]').prop('selected'))
		{
			var temp;

			// Identifiants et niveau de la cat�gorie destination.
			var id_destination;
			var id_first_parent_destination;
			var level_destination;
			$('#categories option').each(function()
			{
				if ($(this).prop('selected'))
				{
					id_destination = $(this).attr('value');
				}
			});

			// On r�cup�re les identifiants des cat�gories s�lectionn�es.
			var id_source;
			var id_parent_source;
			var level_source;
			var object;
			$('.obj_checkbox input').each(function()
			{
				if (!$(this).prop('checked') || !submit)
				{
					return;
				}

				id_source = $(this).attr('id').replace('obj_check_', '');
				temp = $('#map_browse .ig_mc_id_' + id_source).attr('class').split(' ');
				for (t in temp)
				{
					if (temp[t].search(/ig_mc_pid_/) != -1)
					{
						id_parent_source = temp[t].replace('ig_mc_pid_', '');
					}
					else if (temp[t].search(/ig_mc_n_/) != -1)
					{
						level_source = temp[t].replace('ig_mc_n_', '');
					}
				}
				object = $('#obj_' + id_source + ' .obj_group').text();

				// R�gle 1 : la destination et la source sont identiques.
				if (id_source == id_destination)
				{
					submit = false;
				}

				// R�gle 2 : la destination est le parent direct de la source.
				else if (id_parent_source == id_destination)
				{
					submit = false;
				}
			});

			if (!submit)
			{
				alert(object + ' : ' + msg_destination_cat);
			}

			return;
		}
	});

	// Envoi du formulaire.
	$('#form_edit').submit(function()
	{
		if (submit)
		{
			return true;
		}
		else
		{
			submit = true;
			return false;
		}
	});
});

// Section "Albums", suggestion de tags.
jQuery(function($)
{
	if (typeof tags_suggest == 'undefined')
	{
		return;
	}

	var tags_list,el,textarea_tags_id;
	addEvent(document, 'mousedown', function(e)
	{
		el = e.target || event.srcElement;
		if (!el.tagName)
		{
			el = el.parentNode;
		}
		if (el.className != 'tags_list'
		 && el.className != 'tag_li'
		 && el.className != 'tag_ul'
		 && el.className != 'textarea_tags')
		{
			$('.tags_list').remove();
			textarea_tags_id = '';
		}
	});

	var tags_list_click = function()
	{
		var start, end, scrollPos, tag;
		var textarea_val = $(this).parents('.tags_suggest').find('.textarea_tags').val();

		if (textarea_val.search(/^\s*$|,\s+$/) != -1)
		{
			tag = $(this).text();
		}
		else if (textarea_val.search(/,\s*$/) != -1)
		{
			tag = ' ' + $(this).text();
		}
		else
		{
			tag = ', ' + $(this).text();
		}

		$(this).parents('.tags_suggest').find('.textarea_tags')
			.val(textarea_val + tag).focus();
	};

	$('.textarea_tags').focus(function()
	{
		if (!tags_list)
		{
			tags_list = '<span class="tags_list"><ul class="tag_ul">';
			for (var i = 0; i < tags_suggest.length; i++)
			{
				tags_list += '<li class="tag_li">' + tags_suggest[i] + '</li>';
			}
			tags_list += '</ul></span>';
		}

		if (el.className == 'textarea_tags')
		{
			$('.tags_list').remove();
			textarea_tags_id = '';
		}

		if (textarea_tags_id == $(this).attr('id'))
		{
			return;
		}

		$(this).parent().append(tags_list);

		$('.tags_list li').mousedown(function()
		{
			tags_no_blur = true;
		});

		textarea_tags_id = $(this).attr('id');
		$('.tags_list li').click(tags_list_click);
	});
});

// Section "Albums", �dition des images.
jQuery(function($)
{
	if ($('body').attr('id') != 'section_image')
	{
		return;
	}

	// Calcule les dimensions de l'image � partir de l'un des param�tres.
	var prop = function(selector)
	{
		var id = '#resize_tools #';
		switch (selector)
		{
			case 'percent' :
				$(id + 'width').val(Math.round(image_width *
					(parseInt($(id + 'percent').val()) / 100)));
				$(id + 'height').val(Math.round(image_height *
					(parseInt($(id + 'percent').val()) / 100)));
				break;

			case 'width' :
				var ratio = image_height / image_width;
				$(id + 'height').val(Math.round($(id + 'width').val() * ratio));
				$(id + 'percent').val(Math.round(($(id + 'width').val() / image_width) * 100));
				break;

			case 'height' :
				var ratio = image_width / image_height;
				$(id + 'width').val(Math.round($(id + 'height').val() * ratio));
				$(id + 'percent').val(Math.round(($(id + 'height').val() / image_height) * 100));
				break;
		}
	};

	// Retaillage.
	var api = $.Jcrop('#image_crop img', {
		aspectRatio: 0,
		//bgColor: 'none',
		bgOpacity: .4,
		dragEdges: false,
		minSize: [20, 20],
		setSelect: [0, 0, width, height],
		sideHandles: true
	});
	$('#jcrop_all').click(function()
	{
		api.animateTo([0, 0, width, height]);
	});

	// Centrage de l'aper�u.
	var rotate_center = function()
	{
		if ($('#img_rotate').width() < 400)
		{
			var padding_width = Math.floor((400 - $('#img_rotate').width()) / 2);
			$('#img_rotate').parent().css('paddingLeft', padding_width);
			$('#img_rotate').parent().css('paddingRight', padding_width);
		}
		else
		{
			$('#img_rotate').parent().css('paddingLeft', 0);
			$('#img_rotate').parent().css('paddingRight', 0);
		}
		if ($('#img_rotate').height() < 400)
		{
			var padding_height = Math.floor((400 - $('#img_rotate').height()) / 2);
			$('#img_rotate').parent().css('paddingTop', padding_height);
			$('#img_rotate').parent().css('paddingBottom', padding_height);
		}
		else
		{
			$('#img_rotate').parent().css('paddingTop', 0);
			$('#img_rotate').parent().css('paddingBottom', 0);
		}
	};

	// Rotations.
	var webkit_deg = 0;
	$('#rotate_right').click(function()
	{
		orientation = (orientation == 0) ? 270 : orientation - 90;

		// Correction du bug sous Webkit.
		if ($.browser.webkit)
		{
			webkit_deg += 90;
			$('#img_rotate').css('-webkit-transform', 'rotate(' + webkit_deg + 'deg)');
		}
		else
		{
			$('#img_rotate').rotateRight();
			rotate_center();
		}
	});
	$('#rotate_left').click(function()
	{
		orientation = (orientation == 270) ? 0 : orientation + 90;

		// Correction du bug sous Webkit.
		if ($.browser.webkit)
		{
			webkit_deg -= 90;
			$('#img_rotate').css('-webkit-transform', 'rotate(' + webkit_deg + 'deg)');
		}
		else
		{
			$('#img_rotate').rotateLeft();
			rotate_center();
		}
	});

	// Dimensions.
	$('#resize_tools #percent').change(function()
	{
		if ($(this).val().search(/^([1-9]\d?|[1-4]\d{2}|500)$/) == -1)
		{
			$(this).val(100);
		}
		prop('percent');
	});
	$('#resize_tools #width').change(function()
	{
		var percent = Math.round(($(this).val() / image_width) * 100);
		if ($(this).val().search(/^[1-9]\d{0,4}$/) == -1
		|| percent < 1 || percent > 500)
		{
			$(this).val(image_width);
		}
		prop('width');
	});
	$('#resize_tools #height').change(function()
	{
		percent = Math.round(($(this).val() / image_height) * 100);
		if ($(this).val().search(/^[1-9]\d{0,4}$/) == -1
		|| percent < 1 || percent > 500)
		{
			$(this).val(image_height);
		}
		prop('height');
	});

	// Changement d'outil.
	$('#tools .icon').click(function()
	{
		// Lien du menu d'outils.
		$('#tools li').each(function()
		{
			$(this).removeAttr('id', 'current_tool');
		});
		$(this).parents('li').attr('id', 'current_tool');

		// Fonctions de l'outil courant.
		$('.tools').each(function()
		{
			$(this).css('display', 'none');
		});
		$('#' + $(this).attr('id')  + '_tools').css('display', 'block');

		// Aper�u de l'mage.
		$('.image_preview').each(function()
		{
			$(this).css('display', 'none');
		});
		$('#image_' + $(this).attr('id')).css('display', 'block');
	});

	// Envoi du formulaire.
	$('#edit').submit(function()
	{
		var coords = api.tellScaled();
		var new_coords = coords.x + ',' + coords.y + ',' + coords.w + ',' + coords.h;
		$('#edit input[name="crop"]').val(
			current_coords + '.' + new_coords
		);
		$('#edit input[name="rotate"]').val(orientation);
	});
});

// Section "Albums" : �dition en masse.
jQuery(function($)
{
	if (!$('#mass_edit_thumbs').is('div'))
	{
		return;
	}

	// Identifiants de toutes les images de la cat�gorie.
	var images_cat_id;

	// Identifiant des images s�lectionn�es.
	var selection = [];

	// Nombre de colonnes de vignettes.
	var thumbs_cols;

	// Code HTML des vignettes.
	var thumbs_html;

	// Nombre de lignes de vignettes maximum.
	var thumbs_lines = 4;

	// Nombre de vignettes maximum par page.
	var thumbs_max = 16;

	// Nombre de vignettes.
	var thumbs_nb;

	// Page de vignettes courante.
	var thumbs_pages_current = 1;

	// Nombre de pages de vignettes.
	var thumbs_pages_nb;

	// Position de la premi�re vignette de la page courante.
	var thumbs_position_current = 1;

	// Timeout pour la r�cup�ration d'images apr�s
	// redimensionnement de la fen�tre du navigateur.
	var thumbs_resize_timeout;

	// Largeur totale (avec marges externes) d'une vignette.
	var thumbs_width = 124;

	// Effectue le changement de page selon le bouton cliqu�.
	var thumbs_change_pages = function(button)
	{
		switch (button)
		{
			case 'first' :
				thumbs_position_current = 1;
				break;

			case 'prev' :
				thumbs_position_current -= thumbs_max;
				break;

			case 'next' :
				thumbs_position_current += thumbs_max;
				break;

			case 'last' :
				thumbs_position_current = 1 + ((thumbs_max * thumbs_pages_nb) - thumbs_max);
				break;

			default :
				thumbs_position_current = 1 + ((thumbs_max * button) - thumbs_max);
		}

		thumbs_params();
		thumbs_pages();
		$('#mass_edit_thumbs_inner dl').remove();
		$('#mass_edit_thumbs_inner').addClass('loading');

		if (thumbs_html[thumbs_position_current] === undefined
		 || thumbs_html[thumbs_position_current + thumbs_max - 1] === undefined)
		{
			thumbs_get(true);
		}
		else
		{
			thumbs();
			thumbs_pages();
			thumbs_get(false);
		}
	};

	// R�cup�ration des informations par Ajax.
	var thumbs_get = function(init)
	{
		$.post(
			gallery_path + '/ajax.php',
			{
				nb_images: thumbs_max * 4,
				orderby: $('#orderby').val(),
				position: thumbs_position_current,
				q: q,
				section: 'mass-edit',
				sortby: $('#sortby').val()
			},
			function(r)
			{
				if (typeof r != 'object' || r === null)
				{
					return;
				}
				switch (r.status)
				{
					// Erreur.
					case 'error' :
						alert('error: ' + r.msg);
						break;

					// Aucun r�sultat.
					case 'no_result' :
						break;

					// Succ�s.
					default :
						images_cat_id = r.images_cat_id;
						thumbs_html = r.images;
						thumbs_nb = r.nb_images;
						if (init)
						{
							thumbs_params();
							thumbs_pages();
							thumbs();
						}
						$('#position_nb_images').text('[' + r.nb_images + ']');
						break;
				}
			},
			'json'
		);
	};

	// Ajoute le code HTML des vignettes.
	var thumbs = function()
	{
		var html = '';
		var i = 1;
		var test = false;
		for (position in thumbs_html)
		{
			if (position < thumbs_position_current)
			{
				continue;
			}

			var tb_html = thumbs_html[position];

			// Image d�ja s�lectionn�e ?
			if (in_array(tb_html.replace(/^[^$]+id=\"i_(\d+)\"[^$]+$/, '$1'), selection))
			{
				tb_html = tb_html
					.replace('selectable_class', 'selectable_class selected')
					.replace('<input', '<input checked="checked"');
			}

			html += tb_html;
			i++;
			if (i > thumbs_max)
			{
				break;
			}
		}
		$('#mass_edit_thumbs_inner dl').remove();
		$('#mass_edit_thumbs_inner').removeClass('loading').append(html);

		// Case � cocher.
		$('#mass_edit_thumbs_inner .selectable').click(function()
		{
			select($(this), 0);
		});

		// Zone de s�lection.
		$('#mass_edit_thumbs_inner .selectable_zone').click(function()
		{
			select($(this).find('.selectable'), 0);
		});

		// Ajoute ou retire une image � la s�lection
		$('#mass_edit_thumbs_inner dl').click(function()
		{
			var id = $(this).attr('id').replace('i_', '');
			if (in_array(id, selection))
			{
				selection = delete_in_array(id, selection);
			}
			else
			{
				selection.push(id);
			}
			var nb = selection.length;
			var text = (nb > 1) ? locale_select_p : locale_select_s;
			$('#mass_edit_actions .report_info').text(text.replace('%s', nb));
		});
	};

	// G�n�re le code HTML des liens de navigation entre les pages.
	var thumbs_pages = function()
	{
		if (thumbs_nb == 0)
		{
			return;
		}

		// Code HTML de la liste d�roulante.
		var html = '', selected;
		for (i = 1; i <= thumbs_pages_nb; i++)
		{
			selected = (thumbs_pages_current == i)
				? ' selected="selected"'
				: '';
			html += '<option' + selected + ' value="' + i + '">' + i + '</option>';
		}
		$('#mass_edit_thumbs .nav select').html(html);

		// Liens de navigation entre les pages.
		if (thumbs_pages_current > 1)
		{
			$('#mass_edit_thumbs .first')
				.removeClass('inactive')
				.html('<a href="javascript:;" title="'
					+ locale_page_first + '">&lt;&lt;</a>');
			$('#mass_edit_thumbs .first a').click(function(){thumbs_change_pages('first')});
			$('#mass_edit_thumbs .prev')
				.removeClass('inactive')
				.html('<a href="javascript:;" title="'
					+ locale_page_prev + '">&lt;</a>');
			$('#mass_edit_thumbs .prev a').click(function(){thumbs_change_pages('prev')});
		}
		else
		{
			$('#mass_edit_thumbs .first').addClass('inactive').html('&lt;&lt;');
			$('#mass_edit_thumbs .prev').addClass('inactive').html('&lt;');
		}
		if (thumbs_pages_nb > 1 && thumbs_pages_nb != thumbs_pages_current)
		{
			$('#mass_edit_thumbs .next')
				.removeClass('inactive')
				.html('<a href="javascript:;" title="'
					+ locale_page_next + '">&gt;</a>');
			$('#mass_edit_thumbs .next a').click(function(){thumbs_change_pages('next')});
			$('#mass_edit_thumbs .last')
				.removeClass('inactive')
				.html('<a href="javascript:;" title="'
					+ locale_page_last + '">&gt;&gt;</a>');
			$('#mass_edit_thumbs .last a').click(function(){thumbs_change_pages('last')});
		}
		else
		{
			$('#mass_edit_thumbs .next').addClass('inactive').html('&gt;');
			$('#mass_edit_thumbs .last').addClass('inactive').html('&gt;&gt;');
		}
	};

	// Calcul des param�tres d'affichage des vignettes.
	var thumbs_params = function()
	{
		// Nombre de colonnes de vignettes.
		thumbs_cols = Math.floor($('#mass_edit_thumbs_inner').width() / thumbs_width);

		// Nombre de vignettes par page.
		thumbs_max = thumbs_cols * thumbs_lines;

		// Nombre de pages.
		thumbs_pages_nb = Math.ceil(thumbs_nb / thumbs_max);

		// Page courante.
		thumbs_pages_current = Math.ceil(thumbs_position_current / thumbs_max);

		// Nouvelle position courante.
		thumbs_position_current = 1 + ((thumbs_pages_current * thumbs_max) - thumbs_max);

		// Nombres de vignettes par page.
		for (i = 1; i <= 20; i++)
		{
			$('#nb_per_page option[value="' + i + '"]')
				.removeClass('selected')
				.prop('selected', false)
				.text(thumbs_cols * i);
			if (i == thumbs_lines)
			{
				$('#nb_per_page option[value="' + i + '"]')
					.addClass('selected')
					.prop('selected', true);
			}
		}
	};

	// Redimensionnement du navigateur.
	$(window).resize(function()
	{
		clearTimeout(thumbs_resize_timeout);
		thumbs_resize_timeout = setTimeout(function(){thumbs_get(true);}, 150);
	});

	// Changement de page par s�lection du num�ro de page.
	$('#mass_edit_thumbs .nav select').change(function()
	{
		thumbs_change_pages(this.options[this.selectedIndex].value);
	});

	// �largissement de la zone des vignettes.
	$('#mass_edit_thumbs_expand').click(function()
	{
		if ($('#mass_edit_thumbs').hasClass('expand'))
		{
			$('#mass_edit_thumbs').removeClass('expand');
			$(this).addClass('icon_expand').removeClass('icon_collapse');
			$(this).children('a').text(locale_expand);
		}
		else
		{
			$('#mass_edit_thumbs').addClass('expand');
			$(this).removeClass('icon_expand').addClass('icon_collapse');
			$(this).children('a').text(locale_collapse);
		}
		thumbs_get(true);
	});

	// Nombre de vignettes par page.
	$('#nb_per_page').change(function()
	{
		thumbs_lines = this.options[this.selectedIndex].value;
		if (thumbs_html[thumbs_position_current] === undefined
		 || thumbs_html[thumbs_position_current + (thumbs_cols * thumbs_lines) - 1] === undefined)
		{
			thumbs_params();
			thumbs_get(true);
		}
		else
		{
			thumbs_params();
			thumbs_pages();
			thumbs();
			thumbs_get(false);
		}
	});

	// Ordre de tri des images.
	$('#orderby,#sortby').change(function()
	{
		$(this).children('option').removeClass('selected');
		$(this).children('option:selected').addClass('selected');
		thumbs_get(true);
	});

	// S�lection de toutes les images de la page courante.
	$('#page_select_all').click(function()
	{
		$('#mass_edit_thumbs_inner dl').each(function()
		{
			if (!in_array($(this).attr('id').replace('i_', ''), selection))
			{
				$(this).click();
			}
		});
	});

	// Inverse la s�lection.
	$('#page_unselect_all').click(function()
	{
		$('#mass_edit_thumbs_inner dl').each(function()
		{
			if (in_array($(this).attr('id').replace('i_', ''), selection))
			{
				$(this).click();
			}
		});
	});

	// (D�)s�lection de toutes les images de la cat�gorie.
	$('#cat_select_all,#cat_unselect_all').click(function()
	{
		selection = ($(this).attr('id') == 'cat_select_all') ? images_cat_id.slice() : [];
		var nb = selection.length;
		var text = (nb > 1) ? locale_select_p : locale_select_s;
		$('#mass_edit_actions .report_info').text(text.replace('%s', nb));
		thumbs();
	});

	// Ajout la s�lection d'images au formulaire avant l'envoi de celui-ci.
	$('#mass_edit_actions form').submit(function()
	{
		$('#mass_edit_actions input[name="orderby"]')
			.val($('#orderby').children('option:selected').val());
		$('#mass_edit_actions input[name="sortby"]')
			.val($('#sortby').children('option:selected').val());
		$('#mass_edit_actions input[name="selected_ids"]').val(selection);
	});

	// R�cup�ration des vignettes.
	thumbs_get(true);
});

// Section "Tags".
jQuery(function($)
{
	if (!$('#section_tags').is('body'))
	{
		return;
	}

	$('.icon_add').click(function()
	{
		$('#new_tags .field_ftw').append(
		 '<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />' +
		 '<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />' +
		 '<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />' +
		 '<input name="new_tags[]" type="text" class="text" maxlength="255" size="50" />'
		);
	});
});

// Section "Utilisateurs".
jQuery(function($)
{
	if ($('body').attr('id') != 'section_users')
	{
		return;
	}

	var submit = true;

	$('#action_submit').click(function()
	{
		if ($(this).attr('name') != 'selection')
		{
			return;
		}

		// Confirmation sur la suppression d'utilisateurs.
		if ($('#selection_action').children('option[value="delete"]').prop('selected'))
		{
			submit = confirm(confirm_delete);
			return;
		}
	});

	// Envoi du formulaire.
	$('#users_form').submit(function()
	{
		if (submit)
		{
			return true;
		}
		else
		{
			submit = true;
			return false;
		}
	});
});

// Section "Utilisateurs", onglet "Groupes", �dition des groupes.
jQuery(function($)
{
	if ($('body').attr('id') != 'section_group_functions')
	{
		return;
	}

	// Permissions : acc�s total.
	var all_perms = function()
	{
		if ($('input#all_perms').prop('checked'))
		{
			$('#admin_rights input:not(#all_perms)').each(function()
			{
				$(this).prop('disabled', true);
				$(this).removeClass('selectable');
			});
		}
		else
		{
			$('#admin_rights input:not(#all_perms)').each(function()
			{
				$(this).prop('disabled', false);
				$(this).addClass('selectable');
			});
		}
	};
	all_perms();
	$('input#all_perms').click(function()
	{
		all_perms();
	});

	// Permissions : s�lection de parties.
	$('#admin_rights h5 span').click(function()
	{
		var all_checked = true;
		$('#admin_rights input.selectable.' + $(this).attr('class')).each(function()
		{
			if (!$(this).prop('checked'))
			{
				all_checked = false;
			}
		});
		$('#admin_rights input.selectable.' + $(this).attr('class')).each(function()
		{
			if (all_checked)
			{
				$(this).prop('checked', false);
			}
			else
			{
				$(this).prop('checked', true);
			}
		});
	});
});

// Section "Utilisateurs", onglet "Groupes", permissions d'acc�s aux cat�gories.
jQuery(function($)
{
	if ($('body').attr('id') != 'section_group_access'
	 && $('body').attr('id') != 'section_group_upload')
	{
		return;
	}

	// Type de liste s�lectionn�e.
	$('#blacklist,#whitelist').each(function()
	{
		if ($(this).prop('checked'))
		{
			$(this).parents('.group_categories').addClass('select');
		}
	});

	// Modifie le style du type de liste s�lectionn�.
	$('#blacklist,#whitelist').click(function()
	{
		$('.group_categories').removeClass('select');
		$(this).parents('.group_categories').addClass('select');
	});

	// D�plie et replie des cat�gories.
	$('span.p a').click(function()
	{
		if (!$(this).parents('.group_categories').hasClass('select'))
		{
			return;
		}
		if ($(this).parent().hasClass('fold'))
		{
			$(this).parent().children('.p + a + ul').css('display', 'block');
			$(this).parent().removeClass('fold');
			$(this).text('[-]');
		}
		else
		{
			$(this).parent().children('.p + a + ul').css('display', 'none');
			$(this).parent().addClass('fold');
			$(this).text('[+]');
		}
	});

	// Permissions de la liste noire.
	$('#group_blacklist .perm').click(function()
	{
		if (!$(this).parents('.group_categories').hasClass('select'))
		{
			return;
		}
		if ($(this).hasClass('allow'))
		{
			$(this).parent().find('.perm').each(function()
			{
				$(this)
					.removeClass('allow')
					.removeClass('forbidden_child')
					.addClass('forbidden')
					.addClass('by_parent');
			});
			$(this).parents('.cat').each(function()
			{
				$(this).children('.perm')
					.addClass('forbidden_child');
			});
			$(this).removeClass('by_parent').removeClass('forbidden_child');
		}
		else if ($(this).hasClass('forbidden') && !$(this).hasClass('by_parent'))
		{
			$(this).parent().find('.perm').each(function()
			{
				$(this)
					.removeClass('forbidden')
					.removeClass('by_parent')
					.addClass('allow');
			});
			$(this).parents('.cat').each(function()
			{
				if (!$(this).find('ul .perm').hasClass('forbidden'))
				{
					$(this).children('.perm')
						.removeClass('forbidden_child');
				}
			});
		}
	});

	// Permissions de la liste blanche.
	$('#group_whitelist .perm').click(function()
	{
		if (!$(this).parents('.group_categories').hasClass('select'))
		{
			return;
		}
		if ($(this).hasClass('forbidden'))
		{
			$(this).parent().find('.perm').each(function()
			{
				$(this)
					.removeClass('forbidden')
					.removeClass('allow_child')
					.addClass('allow')
					.addClass('by_parent');
			});
			$(this).parents('.cat').each(function()
			{
				$(this).children('.perm')
					.addClass('allow_child');
			});
			$(this).removeClass('by_parent').removeClass('allow_child');
		}
		else if ($(this).hasClass('allow') && !$(this).hasClass('by_parent'))
		{
			$(this).parent().find('.perm').each(function()
			{
				$(this)
					.removeClass('allow')
					.removeClass('by_parent')
					.addClass('forbidden');
			});
			$(this).parents('.cat').each(function()
			{
				if (!$(this).find('ul .perm').hasClass('allow'))
				{
					$(this).children('.perm')
						.removeClass('allow_child');
				}
			});
		}
	});

	// Envoi du formulaire.
	$('form#group_edit').submit(function()
	{
		// Nouvelle liste noire.
		var blacklist = '';
		$('#group_blacklist a.forbidden:not(.by_parent)').each(function()
		{
			blacklist += (blacklist != '' ? ',' : '') + $(this).attr('id').replace('b', '');
		});
		$('input[name="blacklist"]').val(blacklist);

		// Nouvelle liste blanche.
		var whitelist = '';
		$('#group_whitelist a.allow:not(.by_parent):not(.allow_child)').each(function()
		{
			whitelist += (whitelist != '' ? ',' : '') + $(this).attr('id').replace('w', '');
		});
		$('input[name="whitelist"]').val(whitelist);
	});
});

// Section "Utilisateurs", onglet "Options".
jQuery(function($)
{
	if ($('body').attr('id') != 'section_users_options')
	{
		return;
	}

	var delete_info = function()
	{
		var name = $(this).parents('tr').find('td.name input').attr('name');
		if (name.search(/new/) == -1)
		{
			if (!confirm(delete_confirm))
			{
				return;
			}
		}
		$(this).parents('tr').find('td.name input').after(
			'<input value="1" name="' + name.replace('name', 'delete') + '" type="hidden" />'
		);
		$(this).parents('tr').find('td.name input').val('');
		$(this).parents('tr').hide();
	};

	// Ajout d'une nouvelle information de profil.
	$('#profil_infos #add_info').click(function()
	{
		var n = 1;
		while ($('.perso_new_' + n).is('p'))
		{
			n++;
		}
		var html = '<tr><td class="name perso">';

		$('#lang_edition_list li').each(function()
		{
			var code = $(this).find('a').attr('rel');
			html += '<p class="perso_new_' + n + '"'
				+ (($(this).find('input').prop('checked')) ? '' : ' style="display:none;"')
				+ '><label for="perso_new_' + n + '_' + code + '" class="icon_lang icon_'
				+ code + '"></label><input id="perso_new_' + n + '_' + code + '"'
				+ ' name="perso[perso_new_' + n + '][name][' + code + ']"'
				+ ' maxlength="64" size="25" class="text" type="text" /></p>';
		});

		html += '</td>'
			+ '<td><input name="perso[perso_new_' + n + '][activate]" type="checkbox" /></td>'
			+ '<td><input name="perso[perso_new_' + n + '][required]" type="checkbox" /></td>'
			+ '<td><span class="icon icon_delete"><a class="js" href="javascript:;">'
			+ delete_link + '</a></span></td>'
			+ '</tr>';
		$(this).before(html);
		$('.icon_delete').unbind('click').click(delete_info);
		$('.perso_new_' + n + ':visible:first input').focus();
	});

	// Suppression d'une information de profil personnalis�e.
	$('.icon_delete').click(delete_info);
});

// Section "Widgets", "liens externes".
jQuery(function($)
{
	if (!$('#widget_links').is('form'))
	{
		return;
	}

	$('#widget_links .obj_w_sortable').mousedown(function()
	{
		$('.obj_w_edition').hide();
	});

	// Suppression d'un lien.
	var w_delete = function()
	{
		var name = $(this).parents('.obj_w').find('input[type="hidden"]')
			.attr('name').replace(/links\[(.+)\]/, '$1');
		if ($('#new_i_' + name).is('input'))
		{
			$('#i_' + name).remove();
			return;
		}
		$(this).parents('.obj_w').append(
			'<input type="hidden" name="links[' + name + '][delete]" value="1" />'
		);
		$(this).parents('.obj_w').hide();
	};
	$('#widget_links .obj_w_delete .icon_delete').click(w_delete);

	// Ajout d'un lien.
	$('#widget_links .icon_add').click(function()
	{
		$('.obj_w_edition').hide();
		var n = 0;
		while ($('#i_' + n).is('div'))
		{
			n++;
		}
		var html =
		'<div id="i_' + n + '" class="obj_w selected selectable_class">'
			+ '<input type="hidden" name="links[' + n + ']" />'
			+ '<input id="new_i_' + n + '"  type="hidden"'
				+ 'name="links[' + n + '][new]" value="1" />'
			+ '<p class="obj_w_checkbox selectable_zone"><span>'
				+ '<input checked="checked" class="selectable"'
					+ ' name="links[' + n + '][activate]" type="checkbox" />'
			+ '</span></p>'
			+ '<p class="obj_w_sortable"><span></span></p>'
			+ '<p class="obj_w_body">'
				+ '<span class="obj_w_title">' + new_title + '</span>'
			+ '</p>'
			+ '<p class="obj_w_action obj_w_edit">'
				+ '<span><span class="icon icon_edit">'
					+ '<a class="js" href="javascript:;">'
						+ new_edition
					+ '</a>'
				+ '</span></span>'
			+ '</p>'
			+ '<p class="obj_w_action obj_w_delete">'
				+ '<span><span class="icon icon_delete">'
					+ '<a class="js" href="javascript:;">'
						+ new_delete
					+ '</a>'
				+ '</span></span>'
			+ '</p>'
			+ '<div id="obj_w_edition_i_' + n + '" class="obj_w_fold obj_w_edition">'
				+ '<div class="obj_w_edition_inner">';

		// Titre.
		$('#lang_edition_list li').each(function()
		{
			html += '<p' + (($(this).find('input').prop('checked'))
				? '' : ' style="display:none;"') + ' class="field field_ftw">'
				+ '<label class="icon_lang icon_' + $(this).find('a').attr('rel') + '"'
				+ ' for="obj_title_' + n + '_' + $(this).find('a').attr('rel') + '">'
					+ new_field_title
				+ '</label>'
				+ '<input id="obj_title_' + n
					+ '_' + $(this).find('a').attr('rel') + '"'
					+ ' name="links[' + n + '][title]['
					+ $(this).find('a').attr('rel') + ']" type="text"'
					+ ' class="text" maxlength="128" size="40"'
					+ ' value="" />'
			+ '</p>'
		});

		// URL.
		html += '<p class="field field_ftw">'
				+ '<label for="obj_url_' + n + '">'
					+ 'URL :'
				+ '</label>'
				+ '<input id="obj_url_' + n + '"'
					+ ' name="links[' + n + '][url]" type="text"'
					+ ' class="text" maxlength="512" size="40"'
					+ ' value="http://" />'
			+ '</p>';

		// Description.
		$('#lang_edition_list li').each(function()
		{
			html += '<p' + (($(this).find('input').prop('checked'))
				? '' : ' style="display:none;"') + ' class="field field_ftw">'
				+ '<label class="icon_lang icon_' + $(this).find('a').attr('rel') + '"'
				+ ' for="obj_desc_' + n + '_' + $(this).find('a').attr('rel') + '">'
					+ new_field_desc
				+ '</label>'
				+ '<textarea onkeyup="this.value=this.value.slice(0,128)"'
					+ ' id="obj_desc_' + n + '_' + $(this).find('a').attr('rel') + '"'
					+ ' name="links[' + n + '][desc]['
					+ $(this).find('a').attr('rel') + ']" rows="4" cols="40">'
				+ '</textarea>'
			+ '</p>'
		});

		html += '</div></div></div>';
		$('#tools').after(html);

		// On ajoute les �v�nements sur le nouveau lien.
		$('#i_' + n + ' .obj_w_edit .icon_edit').click(w_edition);
		$('#i_' + n + ' .obj_w_delete .icon_delete').click(w_delete);
		$('#i_' + n + ' .obj_w_edit .icon_edit').click();
		$('#i_' + n + ' .obj_w_sortable').mousedown(function()
		{
			$('.obj_w_edition').hide();
		});
		$('#i_' + n + ' .selectable').click(function()
		{
			select($(this), 0);
		});
		$('#i_' + n + ' .selectable_zone').click(function()
		{
			select($(this).find('.selectable'), 0);
		});
	});
});

// Section "Fonctionnalit�s", "filigrane".
jQuery(function($)
{
	if (!$('#watermark_options').is('form'))
	{
		return;
	}

	addEvent(document, 'mousedown', function(e)
	{
		$('.colorpicker_canvas').hide();
	});

	$('.colorpicker').each(function()
	{
		var id = $(this).attr('id');

		$(this).after('<img id="colorpicker_icon_'
			+ id + '" width="16" height="16" alt="" src="'
			+ style_path + '/icons/16x16/color-pencil.png" />');
		$(this).parent('p').after('<div class="colorpicker_canvas" id="colorpicker_'
			+ id + '"></div>');

		$(this).parents('p').find('img').click(function()
		{
			$('#colorpicker_' + id).show();
			$(this).parent().find('input').focus();
		});

		$('#colorpicker_' + id).farbtastic('#' + id);
	});
});

// Section "Options".
jQuery(function($)
{
	// Onglet "Galerie".
	if ($('body').attr('id') == 'section_options_gallery')
	{
		// Format des dates.
		var sel = ['#date_format_list', '#date_format_thumbs_list'];
		for (var i = 0; i < sel.length; i++)
		{
			if ($(sel[i]).val() == 'perso')
			{
				$(sel[i]).prop('disabled', false);
			}
			else
			{
				$(sel[i].replace('_list', '')).prop('disabled', true);
			}
		}
		$('#date_format_list,#date_format_thumbs_list').change(function()
		{
			if ($(this).val() != 'perso')
			{
				$('#' + $(this).attr('id').replace('_list', ''))
					.val($(this).find(':selected').attr('class'))
					.prop('disabled', true);
			}
			else
			{
				$('#' + $(this).attr('id').replace('_list', ''))
					.prop('disabled', false);
			}
		});
	}
});

// Section "Th�mes".
jQuery(function($)
{
	if (!$('#section_themes').is('body'))
	{
		return;
	}

	$('.theme_select,.theme_screenshot img').click(function()
	{
		$('.theme').each(function()
		{
			$(this).removeClass('selected');
			$(this).find('input').prop('checked', false);
		});
		$(this).parents('.theme').find('input').prop('checked', true);
		$(this).parents('.theme').addClass('selected');
	});

	$('select').change(function()
	{
		var theme_name = $(this).parents('.theme').attr('id');
		var style_name = $(this).find('option:selected').val();

		// Informations.
		$('#' + theme_name + ' .theme_author').hide();
		$('#' + theme_name + ' .theme_desc').hide();
		$('#author_' + theme_name + '_' + style_name).show();
		$('#desc_' + theme_name + '_' + style_name).show();

		// Capture d'�cran.
		var screenshot = $(this).parents('.theme').find('img').attr('src')
			.replace(/style\/.+\/screenshot.jpg/, 'style/' + style_name + '/screenshot.jpg');
		$(this).parents('.theme').find('img').attr('src', screenshot);
	});
});

// Section "Maintenance".
jQuery(function($)
{
	if (!$('#section_maintenance').is('body'))
	{
		return;
	}

	$('.fielditems a').click(function()
	{
		submit = true;

		if ($(this).hasClass('confirm'))
		{
			submit = confirm(confirm_delete);
		}

		if (submit)
		{
			$(this).parents('form').find('input[name="tool"]').val($(this).attr('rel'));
			$(this).parents('form').submit();
		}
	});
});

// Section "Incidents".
jQuery(function($)
{
	if (!$('#section_incidents').is('body'))
	{
		return;
	}

	var submit = true;

	$('#link_forum a').click(function()
	{
		if ($('#forum').is(':hidden'))
		{
			$('#forum').show().focus();
		}
		else
		{
			$('#forum').hide();
		}
	});

	$('#action_submit').click(function()
	{
		if ($(this).attr('name') != 'selection')
		{
			return;
		}

		// Confirmation sur la suppression d'incidents.
		if ($('#selection_action').children('option[value="delete"]').prop('selected'))
		{
			submit = confirm(confirm_delete);
			return;
		}
	});

	// Envoi du formulaire.
	$('#incidents').submit(function()
	{
		if (submit)
		{
			return true;
		}
		else
		{
			submit = true;
			return false;
		}
	});

	$('.icon_details').click(function()
	{
		var details_id = '#' + $(this).find('a').attr('rel');
		if ($(details_id + ' td').is(':hidden'))
		{
			$(details_id + ' td,' + details_id + ' th').show();
		}
		else
		{
			$(details_id + ' td,' + details_id + ' th').hide();
		}
	});
});

// Section "Activit�".
jQuery(function($)
{
	if (!$('#section_logs').is('body'))
	{
		return;
	}

	$('.icon_details').click(function()
	{
		var details_id = '#' + $(this).find('a').attr('rel');
		if ($(details_id).is(':hidden'))
		{
			$(details_id).show();
		}
		else
		{
			$(details_id).hide();
		}
	});
});

// Section "G�olocalisation".
jQuery(function($)
{
	if (!$('#section_geoloc_category').is('body')
	 && !$('#section_geoloc_album').is('body')
	 && !$('#section_geoloc_image').is('body'))
	{
		return;
	}

	var marker;

	// Mise � jour des coordonn�es lors du d�placement du marqueur.
	var update_coords = function(marker)
	{
		$('#longitude').val(marker.getPosition().lng());
		$('#latitude').val(marker.getPosition().lat());
	};

	// Cr�ation de la carte.
	var map = new google.maps.Map(
		document.getElementById('gmap_canvas'),
		{
			center: new google.maps.LatLng(25, 5),
			mapTypeId: google.maps.MapTypeId[g_type],
			zoom: 2
		}
	);

	// Cr�ation du marqueur.
	if (g_latitude !== '' && g_longitude !== '')
	{
		var latlng = new google.maps.LatLng(g_latitude, g_longitude);
		map.setZoom(10);
		map.setCenter(latlng);
		create_marker(map, latlng, true);
	}
	$('#add_marker').click(function()
	{
		create_marker(map, map.getCenter());
	});
	function create_marker(map, latlng, no_update)
	{
		if (marker === undefined)
		{
			marker = new google.maps.Marker(
			{
				map: map,
				position: latlng,
				draggable: true
			});
			google.maps.event.addListener(marker, 'drag', function()
			{
				update_coords(marker);
			});
			if (!no_update)
			{
				update_coords(marker);
			}
			$('#add_marker').hide();
			$('#del_marker').show();
		}
	}

	// Suppression du marqueur.
	$('#del_marker').click(function()
	{
		if (marker !== undefined)
		{
			marker.setMap(null);
			marker = undefined;
			$('#longitude').val('');
			$('#latitude').val('');
			$('#add_marker').show();
			$('#del_marker').hide();
		}
	});

	// Recherche d'adresse
	$('#address_search').click(function()
	{
		var address = $('#address').val().replace("\n", ' ');
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'address': address}, function(results, status)
		{
			if (status == google.maps.GeocoderStatus.OK)
			{
				$('#del_marker').click();

				var latlng = results[0].geometry.location;
				create_marker(map, latlng);
				map.setCenter(latlng);
				map.setZoom(10);

				$('#latitude').val(latlng.lat());
				$('#longitude').val(latlng.lng());
			}
			else
			{
				alert(g_unknown_address);
			}
		});
	});

	// Choix d'un lieu connu.
	$('#places').change(function()
	{
		var selected = $(this).children('option:selected');
		var place = selected.text();
		var coords = selected.val().split(';');
		$('#latitude').val(coords[0]);
		$('#longitude').val(coords[1]);
		$('#place').val(place);

		$('#del_marker').click();
		if (selected.val() == ';')
		{
			var latlng = new google.maps.LatLng(25, 5);
			map.setCenter(latlng);
			map.setZoom(2);
		}
		else
		{
			var latlng = new google.maps.LatLng(coords[0], coords[1]);
			create_marker(map, latlng);
			map.setCenter(latlng);
			map.setZoom(10);
		}
	});
});

// Section "Pages / Carte du monde".
jQuery(function($)
{
	if (!$('#page_worldmap').is('form'))
	{
		return;
	}

	// Cr�ation de la carte.
	var map = new google.maps.Map(
		document.getElementById('gmap_canvas'),
		{
			center: new google.maps.LatLng(geoloc_center_lat, geoloc_center_long),
			mapTypeId: google.maps.MapTypeId[geoloc_type],
			zoom: geoloc_zoom
		}
	);

	// Envoi du formulaire avec les nouvelles options.
	$('form#page_worldmap').submit(function()
	{
		var latlng = map.getCenter();
		$('input[name="center_lat"]').val(latlng.lat());
		$('input[name="center_long"]').val(latlng.lng());
		$('input[name="zoom"]').val(map.getZoom());
	});
});

// Jcrop.
jQuery(function($)
{
	if (typeof jcrop == 'undefined')
	{
		return;
	}

	// Rognage des vignettes : aper�u.
	var show_preview = function(coords)
	{
		var rx = jcrop.thumb_width / coords.w;
		var ry = jcrop.thumb_height / coords.h;

		if (coords.w == 0 && coords.h == 0)
		{
			return;
		}

		if (jcrop.thumb_ratio == 0)
		{
			// Rognage libre.
			// Dimensions du cadre contenant l'image de l'aper�u.
			var width = jcrop.thumb_width;
			var height = jcrop.thumb_height;
			if (coords.w < coords.h)
			{
				width = ry * coords.w;
			}
			else
			{
				height = rx * coords.h;
			}
			rx = width / coords.w;
			ry = height / coords.h;
			$('#preview').css(
			{
				width: width + 'px',
				height: height + 'px'
			});

			// Marges externes du cadre contenant l'image de l'aper�u.
			var top = 0;
			var right = 0;
			var bottom = 0;
			var left = 0;
			var max = (jcrop.thumb_width > jcrop.thumb_height)
				? jcrop.thumb_width
				: jcrop.thumb_height;
			if (width < max)
			{
				right = (max - width) / 2;
				left = right;
			}
			if (height < max)
			{
				top = (max - height) / 2;
				bottom = top;
			}
			$('#preview').css(
			{
				marginTop: top + 'px',
				marginRight: right + 'px',
				marginBottom: bottom + 'px',
				marginLeft: left + 'px'
			});
		}

		$('#preview img').css(
		{
			width: Math.round(rx * jcrop.preview_width) + 'px',
			height: Math.round(ry * jcrop.preview_height) + 'px',
			marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	};

	// Rognage des vignettes : Jcrop.
	var api = $.Jcrop('#image img', {
		aspectRatio: jcrop.thumb_ratio,
		bgOpacity: .4,
		dragEdges: false,
		onChange: show_preview,
		onSelect: show_preview,
		minSize: jcrop.min_size,
		setSelect: jcrop.set_select,
		sideHandles: (jcrop.thumb_ratio == 0) ? true : false
	});
	$('#jcrop_all').click(function()
	{
		api.animateTo([0, 0, jcrop.preview_width, jcrop.preview_height]);
	});
	$('#edit').submit(function()
	{
		var coords = api.tellScaled();
		var new_coords = coords.x + ',' + coords.y + ',' + coords.w + ',' + coords.h;
		var old_coords = jcrop.set_select;
		$('#crop_coords').val(old_coords + '.' + new_coords);
	});
});