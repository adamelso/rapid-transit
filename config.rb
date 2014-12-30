add_import_path "web/components/foundation/scss"
add_import_path "web/components/foundation-icon-fonts"

http_path = "/"
css_dir = "web/assets/stylesheets"
sass_dir = "assets/scss"
images_dir = "web/assets/images"
javascripts_dir = "web/assets/js"
fonts_dir = "web/assets/fonts"

output_style = :compact

relative_assets = true

line_comments = false

if Gem.loaded_specs["sass"].version >= Gem::Version.create('3.4')
  warn "You're using Sass 3.4 or higher to compile Foundation. This version causes CSS classes to output incorrectly, so we recommend using Sass 3.3 or 3.2."
  warn "To use the right version of Sass on this project, run \"bundle\" and then use \"bundle exec compass watch\" to compile Foundation."
end
