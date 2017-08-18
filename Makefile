info:
	@echo "Usage: make test|install"

# pass any target to composer
$(MAKECMDGOALS):
	composer $(MAKECMDGOALS)
