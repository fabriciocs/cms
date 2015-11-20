App
		.factory('Empresa', ['$resource', 'Album', function ($resource, Album) {
				var Server = $resource('rest/index.php/empresa/');
				function Empresa(obj) {
					if (obj && (typeof obj !== undefined)) {
						this.id = obj.id;
						this.nome = obj.nome;
						this.album = obj.album;
						this.historia = obj.historia;
						this.slogan = obj.slogan;
						this.resumo = obj.resumo;
						this.idGoogleAnalytics = obj.idGoogleAnalytics;
						this.missao = obj.missao;
						this.visao = obj.visao;
						this.valores = obj.valores;
						this.dominio = obj.dominio;
						this.urlLogo = obj.urlLogo;
						this.telefone = obj.telefone;
						this.endereco = obj.endereco;
						this.emailContato = obj.emailContato;
						this.iframeGoogleMaps = obj.iframeGoogleMaps;
						this.nomeTema = obj.nomeTema;
						this.nomeCorTema = obj.nomeCorTema;
						this.temaDark = obj.temaDark;
						this.temaFullWidth = obj.temaFullWidth;
						this.facebookPageUrl = obj.facebookPageUrl;
						this.album = new Album(obj.album);
					}
				}

				Empresa.load = function () {
					return Server.get();
				};

				Empresa.prototype.salvar = function () {
					var self = this;
					return Server.save(self);
				};

				return Empresa;
			}]);