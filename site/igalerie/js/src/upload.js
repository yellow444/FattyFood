/**
 * Gestionnaire d'envoi d'images.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
function Upload(prefix, options)
{
	// Options.
	this.options;



	// Index du fichier courant.
	var fileIndex = 0;

	// Code HTML se trouvant dans la liste des fichiers.
	var innerList = $('#' + prefix + 'list').html();

	// Liste des fichiers ajoutés.
	var list = [];

	// Indique si au moins un fichier a été envoyé avec succès.
	var success = false;

	// Indique si on a commencé l'upload.
	var start = false;

	// This!
	var This = this;

	// Total des fichiers envoyés.
	var totalUploadFiles;



	// Options.
	This.options = options;

	// Bouton "Ajouter des fichiers".
	if ($('#' + prefix + 'add').length == 1)
	{
		$('#' + prefix + 'input_file')
			.hide()
			.on('change', function(f)
			{
				addFiles(this.files);
			});
		$(document).on('click', '#' + prefix + 'add', function()
		{
			if (!start)
			{
				$('#' + prefix + 'input_file').click();
			}
			return false;
		});
	}

	// Bouton "Vider la liste".
	if ($('#' + prefix + 'clear').length == 1)
	{
		$(document).on('click', '#' + prefix + 'clear', function()
		{
			if ($(this).hasClass('disabled'))
			{
				return false;
			}
			$(this).addClass('disabled');

			// On réinitialise tout.
			fileIndex = 0;
			list = [];

			// On supprime la liste de fichiers.
			$('.' + prefix + 'file').remove();

			// On remet le contenu de démarrage.
			$(innerList).appendTo('#' + prefix + 'list');

			// Mise à jour du nombre de fichiers.
			updateInterface();

			return false;
		});
	}

	// Bouton "Envoyer".
	if ($('#' + prefix + 'start').length == 1)
	{
		$(document).on('click', '#' + prefix + 'start', function()
		{
			if ($(this).hasClass('disabled') || start || !list.length)
			{
				return false;
			}

			// Si aucun album sélectionné, on ne va pas plus loin.
			if (!This.options.ajaxData.id)
			{
				alert(This.options.l10n.noAlbum);
				return false;
			}

			// On indique que l'on vient de démarrer l'envoi,
			// et on désactive les boutons d'ajout et d'envoi.
			start = true;
			$('#' + prefix + 'add,#' + prefix + 'start').addClass('disabled');

			// On scroll en haut de la liste de fichiers
			// avant de démarrer l'envoi des fichiers.
			$('#' + prefix + 'list').animate({ scrollTop: 0 }, 250, 'swing', function()
			{
				setTimeout(function()
				{
					fileIndex = 0;
					totalUploadFiles = getTotalFiles();
					totalUploadFiles.loadedFiles = 0;
					totalUploadFiles.loadedSize = 0;
					uploadFiles();
				}, 250);
			});

			return false;
		});
	}

	// Suppression d'une image.
	$(document).on('click', '.' + prefix + 'file_delete', function()
	{
		if (!$(this).parents('.' + prefix + 'file').hasClass(prefix + 'file_warning'))
		{
			var i = $(this).parents('.' + prefix + 'file').attr('id')
				.replace(prefix + 'file_', '');
			list[i] = false;
		}

		// On supprime le code HTML du fichier.
		$(this).parents('.' + prefix + 'file').animate(
		{
			opacity: 0,
			height: 0
		},
		{
			duration: 300,
			easing: 'swing',
			complete: function()
			{
				$(this).remove();
				if (!start && $('.' + prefix + 'file').length == 0)
				{
					$(innerList).appendTo('#' + prefix + 'list');
				}

				// Mise à jour du nombre de fichiers.
				updateInterface();
			}
		});
	});

	// Drag & drop.
	$(document).on('dragover', '#' + prefix + 'list', function()
	{
		if (!start)
		{
			$(this).removeClass().addClass('dragover');
		}
		return false;
	});
	$(document).on('dragleave', '#' + prefix + 'list', function()
	{
		if (!start)
		{
			$(this).removeClass().addClass('dragleave');
		}
		return false;
	});
	$(document).on('drop', '#' + prefix + 'list', function(e)
	{
		if (!start
		&& e.originalEvent.dataTransfer
		&& e.originalEvent.dataTransfer.files.length)
		{
			$(this).removeClass().addClass('drop');
			addFiles(e.originalEvent.dataTransfer.files);
		}
		else
		{
			$(this).removeClass();
		}
		return false;
	});

	updateInterface();



	/**
	 * Ajout de nouveaux fichiers.
	 *
	 * @param object files
	 * @return void
	 */
	function addFiles(files)
	{
		if (start)
		{
			return;
		}

		// On supprime tout le code HTML présent dans la liste des fichiers.
		$('#' + prefix + 'list > :not(.' + prefix + 'file)').remove();

		// On traite les fichiers ajoutés.
		for (var i = 0; i < files.length; i++)
		{
			var html = {},
				file_check = checkFile(files[i]);

			// On vérifie le fichier.
			if (file_check == true)
			{
				html.progress = '<div><div></div></div>';
				html.id = '<div id="' + prefix + 'file_' + fileIndex
					+ '" class="' + prefix + 'file">';
			}
			else
			{
				html.progress = file_check;
				html.id = '<div class="' + prefix + 'file ' + prefix + 'file_warning">';
			}

			// Code HTML du fichier.
			var html = html.id + '<div class="' + prefix + 'file_body"><a class="' + prefix
				+ 'file_delete" href="javascript:;"></a><div class="' + prefix
				+ 'file_infos"><span class="' + prefix + 'file_name">'
				+ formatFilename(files[i].name) + '</span><span class="' + prefix
				+ 'file_size">(' + formatFilesize(files[i].size) + ')</span></div><div class="'
				+ prefix + 'file_progress">' + html.progress + '</div></div></div>';
			$(html).appendTo('#' + prefix + 'list');

			// On ajoute le fichier à la liste.
			if (file_check == true)
			{
				list[fileIndex] = files[i];
				fileIndex++;
			}
		}

		// On scroll en bas de la liste.
		$('#' + prefix + 'list').animate(
			{ scrollTop: $('#' + prefix + 'list')[0].scrollHeight }, 1000
		);

		// Si la liste n'est pas vide, on réactive le bouton d'envoi.
		if (list.length)
		{
			$('#' + prefix + 'start').removeClass('disabled');
		}

		// Mise à jour du nombre de fichiers.
		updateInterface();
	};

	/**
	 * Vérifie un fichier.
	 *
	 * @parram object file
	 * @return boolean
	 */
	function checkFile(file)
	{
		var total = getTotalFiles();

		// Nombre de fichiers.
		if (total.files == This.options.maxTotalFiles)
		{
			return getFileError('totalfiles');
		}

		// Nom de fichier.
		if (!file.name.match(/^.{1,250}\.(gif|jpe?g|png)$/i))
		{
			return getFileError('filename');
		}

		// Type de fichier.
		if ($.inArray(file.type, ['image/gif', 'image/jpeg', 'image/png']) == -1)
		{
			return getFileError('filetype');
		}

		// Poids du fichier.
		if (file.size > This.options.maxFilesize)
		{
			return getFileError('filesize');
		}

		// Poids total.
		if ((total.size + file.size) > This.options.maxTotalSize)
		{
			return getFileError('totalsize');
		}

		// Noms de fichiers identiques.
		for (var i = 0; i < list.length; i++)
		{
			if (list[i] && list[i].name == file.name)
			{
				return getFileError('sameFilename');
			}
		}

		return true;
	};

	/**
	 * Formate le nom de fichier.
	 *
	 * @param string str
	 * @return string
	 */
	function formatFilename(str)
	{
		// On limite la longueur du nom de fichier.
		var max = This.options.maxFileNameLength;
		if (max)
		{
			max -= 9;
			if (str.length > max)
			{
				var regexp = new RegExp('^(.{' + max + '}).+(\..{3,4})$');
				str = str.replace(regexp, '$1[...]$2')
			}
		}

		return str;
	};

	/**
	 * Formate le poids d'un fichier.
	 *
	 * @param integer filesize
	 * @param string unit
	 * @return string
	 */
	function formatFilesize(filesize, unit)
	{
		var kb = 1024,
			mb = 1024 * kb;

		// Forcer l'utilisation d'une unité ?
		if (unit)
		{
			unit = (unit == 'kb') ? kb : mb;
			filesize = String(Math.round(filesize/unit * 100) / 100);
		}
		else
		{
			filesize = (filesize < mb)
				? This.options.l10n.sizeUnits[0].replace(
					'%s', Math.round(filesize/kb * 100) / 100
				)
				: This.options.l10n.sizeUnits[1].replace(
					'%s', Math.round(filesize/mb * 100) / 100
				);
		}

		return filesize.replace('.', This.options.l10n.decimalPoint);
	};

	/**
	 * Retourne le code HTML d'un message d'erreur.
	 *
	 * @param string type
	 * @return string
	 */
	function getFileError(type)
	{
		return '<p class="' + prefix + 'warning">' + This.options.l10n.warning[type] + '</p>';
	};

	/**
	 * Retourne le nombre et le poids total des fichiers ajoutés à la liste.
	 *
	 * @return object
	 */
	function getTotalFiles()
	{
		for (var i = 0, n = 0, size = 0; i < list.length; i++)
		{
			if (list[i])
			{
				n++;
				size += list[i].size;
			}
		}
		return { files: n, size: size };
	};

	/**
	 * Met à jour divers éléments d'interface
	 * lorsqu'on ajoute ou supprime des fichiers.
	 *
	 * @return void
	 */
	function updateInterface()
	{
		if (start)
		{
			return;
		}

		// Informations sur la liste.
		var total = getTotalFiles();
		$('#' + prefix + 'infos_images').text(
			total.files + '/' + This.options.l10n.images.replace('%s', This.options.maxTotalFiles)
		);
		$('#' + prefix + 'infos_filesize').text(
			formatFilesize(total.size, 'mb') + '/' + formatFilesize(This.options.maxTotalSize)
		);

		// Boutons.
		if (!total.files)
		{
			$('#' + prefix + 'start').addClass('disabled');
		}

		if ($('.' + prefix + 'file').length)
		{
			$('#' + prefix + 'clear').removeClass('disabled');
		}
		else
		{
			$('#' + prefix + 'clear').addClass('disabled');
		}
	};

	/**
	 * Envoi les fichiers.
	 *
	 * @return void
	 */
	function uploadFiles()
	{
		var f = list[fileIndex],
			size_loaded_prev = 0;

		// Envoi du prochain fichier.
		var next = function()
		{
			fileIndex++;
			uploadFiles();
		};

		// S'il n'y a pas de fichier.
		if (!f)
		{
			// Si le fichier a été supprimé, on passe au suivant.
			if (f === false)
			{
				next();
			}

			// Fin de l'upload.
			else if (success)
			{
				$('#' + prefix + 'form').submit();
			}
			else
			{
				start = false;
				$('#' + prefix + 'add,#' + prefix + 'start').removeClass('disabled');
			}

			return;
		}

		// Lecture du fichier.
		var reader = new FileReader();
		reader.readAsDataURL(f);
		reader.onerror = function(e)
		{
			console.log('File could not be read (' + e.target.error.code + ')');
		};
		reader.onload = function(e)
		{
			// Envoi du fichier.
			This.options.ajaxData.filename = encodeURI(f.name);
			This.options.ajaxData.filedata = e.target.result.split(',')[1];
			$.ajax(
			{
				type: 'POST',
				url: This.options.ajaxScript,
				data: This.options.ajaxData,
				dataType: 'json',
				xhr: function()
				{
					var xhr = new XMLHttpRequest();
					xhr.upload.onprogress = function(e)
					{
						if (e.lengthComputable)
						{
							// Pourcentage du fichier envoyé.
							var pc = Math.round((e.loaded * 100) / e.total);
							$('#' + prefix + 'file_' + fileIndex
							+ ' .' + prefix + 'file_progress div div')
								.css({width: pc + '%'});

							// Pourcentage de tous les fichiers envoyés.
							var size_loaded = (e.loaded / e.total) * f.size;
							totalUploadFiles.loadedSize += (size_loaded - size_loaded_prev);
							$('#' + prefix + 'infos_progress_pc').text(
								Math.round((totalUploadFiles.loadedSize * 100)
								/ totalUploadFiles.size) + '%'
							);
							size_loaded_prev = size_loaded;
						}
					};
					return xhr;
				},

				// Envoi terminé.
				complete: function(r)
				{
					if (typeof r.responseJSON == 'object')
					{
						var msg = r.responseJSON.message;
						if (msg != '')
						{
							msg = ' : ' + encodeURI(msg);
						}
						$('#' + prefix + 'list').after(
							'<input name="' + r.responseJSON.status + '[]" type="hidden" value="'
								+ encodeURI(r.responseJSON.filename) + msg + '" />'
						);
					}

					// Au suivant.
					next();
				},

				// Une erreur ?
				error: function(r)
				{
					$('#' + prefix + 'file_' + fileIndex).addClass(prefix + 'file_error');
					$('#' + prefix + 'file_' + fileIndex + ' .' + prefix + 'file_progress').html(
						'<p class="' + prefix + 'error">' + This.options.l10n.failed + '</p>'
					);
					console.log(r.responseText);
				},

				// Fichier envoyé avec succès.
				success: function()
				{
					var file_index = fileIndex;

					// On indique que l'envoi a été un succès.
					$('#' + prefix + 'file_' + fileIndex).addClass(prefix + 'file_success');
					$('#' + prefix + 'file_' + fileIndex + ' .' + prefix + 'file_progress').html(
						'<p class="' + prefix + 'error">' + This.options.l10n.success + '</p>'
					);

					// Puis on retire le fichier de la liste.
					setTimeout(function()
					{
						$('#' + prefix + 'file_' + file_index
							+ ' .' + prefix + 'file_delete').click();
					}, 2000);

					// On indique qu'au moins un fichier a été envoyé avec succès.
					success = true;
				}
			});
		};
	};
};