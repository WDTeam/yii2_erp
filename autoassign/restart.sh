echo "Reloading..."
cmd=$(pidof autoassign-server)

kill -USR1 "$cmd"
echo "Reloaded"