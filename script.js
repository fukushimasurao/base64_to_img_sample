// https://zenn.dev/tatsuyasusukida/articles/84e789bea68b1e
// 9, 38行目追記
async function main() {
  try {
    const video = document.querySelector("#video"); // <1>
    const button = document.querySelector("#button");
    const image = document.querySelector("#image");

    const base64_image = document.getElementById("base64_image");

    const stream = await navigator.mediaDevices.getUserMedia({
      // <2>
      video: {
        facingMode: "user",
        // facingMode: 'environment',
      },
      audio: false,
    });

    video.srcObject = stream; // <3>

    const [track] = stream.getVideoTracks();
    const settings = track.getSettings();
    const { width, height } = settings; // <4>

    button.addEventListener("click", (event) => {
      // <5>
      const canvas = document.createElement("canvas"); // <6>
      canvas.setAttribute("width", width);
      canvas.setAttribute("height", height);

      const context = canvas.getContext("2d");
      context.drawImage(video, 0, 0, width, height); // <7>

      const dataUrl = canvas.toDataURL("image/jpeg"); // <8>
      image.setAttribute("src", dataUrl); // <9>

      // htmlのform部分に、base64データ挿入する
      base64_image.value = dataUrl;
    });
  } catch (err) {
    console.error(err);
  }
}

main();
