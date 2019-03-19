package main

import (
	"fmt"
	"os"

	"github.com/DATA-DOG/godog"
)

func main() {
	fmt.Println("Working! The go environment is set up")
	var opt = godog.Options{
		Output: colors.Colored(os.Stdout),
		Format: "progress", // can define default values
	}

}
