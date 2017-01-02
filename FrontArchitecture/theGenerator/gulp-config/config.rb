# Nombre de décimal après la virgule, utile pour la précision des em
Sass::Script::Number.precision = 8

encoding = "utf-8"

# Configuration des chemins des ressources:
css_dir         = "/css"
sass_dir        = "scss"
images_dir      = "/images"
javascripts_dir = "/js"
fonts_dir       = "/fonts"

# Configuration des chemins HTTP
http_path             = '/bundles/planmylife'

add_import_path "common-design/scss"

# Configuration spécifique à chaque environement
if  environment == :production
    # options de sortie
    output_style = :compressed
else
    # options de sortie
    output_style =   :expanded
end

require 'sass-globbing'

# Supprimer le hash générer par compass sur le nom des sprites
on_sprite_saved do |filename|
    if File.exists?(filename)
        FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
    end
end

# Remplacer les reférence au sprite avec hash dans la CSS par ceux sans hash
on_stylesheet_saved do |filename|
    if File.exists?(filename)
        css = File.read filename
        File.open(filename, 'w+') do |f|
            f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
        end
    end
end