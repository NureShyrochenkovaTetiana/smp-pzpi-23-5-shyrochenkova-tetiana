#!/bin/bash

VERSION="1.0"

print_help() {
    echo "Використання: $(basename "$0") [--help | --version] | [[-q|--quiet] [файл.csv] [група]]"
}

print_version() {
    echo "$(basename "$0") версія $VERSION"
}

quiet=false
if [[ "$1" == "-q" || "$1" == "--quiet" ]]; then
    quiet=true
    shift
fi

if [[ "$1" == "--help" ]]; then
    print_help
    exit 0
elif [[ "$1" == "--version" ]]; then
    print_version
    exit 0
fi

# Check if argument is passed, otherwise search for CSV files
if [[ -z "$1" ]]; then
    files=( $(find "$HOME" -type f -name "TimeTable_??_??_20??.csv" 2>/dev/null) )
    if [[ ${#files[@]} -eq 0 ]]; then
        echo "Файлы не найдены."
        exit 1
    fi
    echo "Выберите файл:"
    select file in "${files[@]}"; do
        if [[ -n "$file" ]]; then
            selected_file="$file"
            break
        else
            echo "Некорректный выбор, попробуйте снова."
        fi
    done
else
    selected_file="$1"
fi

echo "Вы выбрали: $selected_file"

# Convert encoding from WINDOWS-1251 to UTF-8 and remove carriage returns
tmp_path="/tmp/$(basename "$selected_file")"
iconv -f WINDOWS-1251 -t UTF-8 "$selected_file" | tr '\r' '\n' > "$tmp_path"

# Group options
groups=(
  "ПЗПІ-23-1"
  "ПЗПІ-23-2"
  "ПЗПІ-23-3"
  "ПЗПІ-23-4"
  "ПЗПІ-23-5"
  "ПЗПІ-23-6"
  "ПЗПІ-23-7"
  "ПЗПІ-23-8"
  "ПЗПІ-23-9"
)

# Prompt for group selection if no argument passed
if [[ -z "$2" ]]; then
echo "$3"
echo "$2"
    while true; do
        echo "Выберите группу:"
        for i in "${!groups[@]}"; do
            echo "$((i + 1)). ${groups[$i]}"
        done
        read -p "Введите номер группы: " selected_number
        if [[ "$selected_number" -ge 1 && "$selected_number" -le ${#groups[@]} ]]; then
            selected_group="${groups[$((selected_number - 1))]}"
            break
        else
            echo "Некорректный выбор, попробуйте снова."
        fi
    done
else
        selected_group=$2
fi

echo "Вы выбрали группу: $selected_group"

# Define output file
OUTPUT_FILE="$HOME/Google_$(basename "$selected_file")"
echo "\"Subject\",\"Start Date\",\"Start Time\",\"End Date\",\"End Time\"" > "$OUTPUT_FILE"

# Process the CSV and filter by group
awk -v group="$selected_group" 'BEGIN {
    FPAT = "([^,]+)|(\"[^\"]+\")"  # Handle CSV quoting
    OFS = ","
}
NR > 1 {
    for(i = 1; i <= NF; i++) {
        sub(/^"/, "", $i)
        sub(/"$/, "", $i)
    }

    # Check if the Subject matches the selected group
    if ($1 ~ "^" group " -") {
        start_date = $2
        end_date = $4
        start_time = $3
        end_time = $5

        # Reformat the date from DD.MM.YYYY to MM/DD/YYYY
        split(start_date, start_arr, ".")
        start_date = start_arr[2] "/" start_arr[1] "/" start_arr[3]
        split(end_date, end_arr, ".")
        end_date = end_arr[2] "/" end_arr[1] "/" end_arr[3]

        # Standardize the start and end times to 24-hour format (HH:MM) without seconds
        start_time = (start_time ~ /AM|PM/) ? strftime("%H:%M", mktime("1970 01 01 " start_time)) : substr(start_time, 1, 5)
        end_time = (end_time ~ /AM|PM/) ? strftime("%H:%M", mktime("1970 01 01 " end_time)) : substr(end_time, 1, 5)

        # Print the formatted line for CSV output
        print "\"" $12 "\",\"" start_date "\",\"" start_time "\",\"" end_date "\",\"" end_time "\",\"" $12 "\""
    }
}' "$tmp_path" >> "$OUTPUT_FILE"
echo "Результат збережен у $OUTPUT_FILE"

if [[ "$quiet" == "false" ]]; then
    cat "$OUTPUT_FILE"
fi
		
