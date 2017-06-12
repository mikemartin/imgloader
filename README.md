# Imgloader

This addon creates data attributes for responsive image sizes

## Usage

```
<div
data-src="{{ imgloader:load src='{ hero }' inline='true' maxW='750' q='100' }}"
class="js-img-load"></div>
```

## Outputs

```
<div
data-src="/image.jpg"
data-s="/image-r-w600-q100.jpg"
data-m="/image-r-w750-q100.jpg"
class="js-img-load"></div>
```
