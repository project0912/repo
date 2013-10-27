# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "css/scss"
images_dir = "img"
javascripts_dir = "js"

encoding = "utf-8"

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

watch "blocks*/**/*" do |project_dir, relative_path|
  exec('compass watch')
end

line_comments = false # by Fire.app

output_style = :expanded # by Fire.app